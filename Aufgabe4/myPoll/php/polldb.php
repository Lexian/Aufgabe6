<?php

/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 02.06.15
 * Time: 02:34
 */
class PollDB
{
    protected $pdo;                     //mein zukünftiges Objekt
    protected $showerror = TRUE;           //fürs debugging
    protected $showsql = TRUE;           //fürs debugging
    protected $sqlcounter = 0;     // zähler für SQL commands
    protected $rowcounter = 0;     //  zähler für returned SELECT rows
    protected $dbtime = 0;     // zähler für fie benötigte Zeit zum ausführen von queries
    protected $starttime;

    function __construct()
    {
        require_once('../admin/password.php');
        try {
            $tmpUSr = base64_decode($db_usr);
            $tmpPASS = base64_decode($db_pass);
            $this->pdo = new PDO($dsn, $tmpUSr, $tmpPASS);
            $this->starttime = $this->microtime_float();
        } catch (PDOException $e) {
            html_start('MySQL Verbindungsfehler');
            $this->printerror("Keine Verbindung möglich (" . $e->getMessage() . ")"); // bei Verbindungsfehlern
            htm_end();
            $this->pdo = NULL;
            exit();
        }


    }    // Helfer Variable zum holen des richtigen datensatzes

    // erstellt ein Objekt  meiner Datenbankverbindung

    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    // zum schliessen

    private function printerror($txt)
    {
        if ($this->showerror)
            printf("<p><font color=\"#ff0000\">%s</font></p>\n",
                htmlentities($txt));                                // setzt die Schriftfarbe auf rot und printed den Text rot aus.
    }

    /**
     * @return mixed
     */
    public function getTitleHelper()
    {
        return $this->title_helper;
    }

    function __destruct()
    {
        $this->close();
    }

    function close()
    {
        // schliesst meine Verbindung
        if ($this->pdo)
            $this->pdo = NULL;
    }

    // ausführen meiner Select quer(y/ies), und gibt ein array Object zurück

    /**
     * @return null
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    function queryObjectArray($sql)
    {
        $this->sqlcounter++; // erhöht den Zähler bei jeden Aufruf um 1
        //$this->printSql($sql);
        $time1 = $this->microtime_float();      // Startzeit der Bearbeitung
        $result = $this->pdo->query($sql);        //bearbeiten des queries //TODO prüfen ob das so funktioniert...
        $time2 = $this->microtime_float();      //endzeit der Bearbeitung
        $this->dbtime += ($time2 - $time1);     // Berechnung der Zeit die verbraucht wurde für diese bearbeitung

        if ($result === FALSE) {
            $errorInfo = $this->pdo->errInfo(); // druckt fehler in das array
            $this->prinerror($errorInfo[2]);
            return -1;
        } else {
            $tmp = $result->fetchAll(PDO::FETCH_OBJ);
            return $tmp;
        }
    }

    //Ausführen eines select queries welches nur ein einziges Item zurück gibt

    function queryArray($sql)
    {
        $this->sqlcounter++;
        //  $this->printsql($sql);
        $time1 = $this->microtime_float();
        $result = $this->pdo->query($sql);
        $time2 = $this->microtime_float();
        $this->dbtime += ($time2 - $time1);

        if ($result === FALSE) {
            $errInfo = $this->pdo->errorInfo();
            $this->printerror($errInfo[2]);
            return -1;
        } else {
            return $result->fetchAll(PDO::FETCH_BOTH);
        }
    }

    function querySingleItem($sql)
    {
        $this->sqlcounter++;        //es wiederholt sich mal wieder alles, denn wir wollen ja wissen wie lange der kleine Scheisser dafür braucht
        // $this->printsql($sql);
        $time1 = $this->microtime_float();
        $result = $this->pdo->query($sql);
        $time2 = $this->microtime_float();
        $this->dbtime += ($time2 - $time1);

        if ($result === FALSE) {
            $errInfo = $this->pdo->errorInfo();
            $this->printerror($errInfo[2]);
            return -1;
        } else {
            $singleItem = $result->fetchColumn(0);
            $result->closeCursor();
            return $singleItem;
        }

    }

    function execute($sql)
    {
        $this->sqlcounter++;
        // $this->printsql($sql);
        $time1 = $this->microtime_float();
        $result = $this->pdo->exec($sql);
        $time2 = $this->microtime_float();
        $this->dbtime += ($time2 - $time1);
        if ($result)
            return TRUE;
        else {
            $errInfo = $this->pdo->errorInfo();
            $this->printerror($errInfo[2]);
            return FALSE;
        }
    }

    function insertId()
    {               // holt sich die inserID nach dem letzten INSERT
        return $this->pdo->lastInsertID();
    }

    function sql_string($txt)
    {         // gibt 'NULL' oder '<quoted string>' zurück
        if (!$txt || trim($txt) == "")
            return 'NULL';
        else
            return $this->quote(trim($txt));
    }

    function quote($txt)
    {              // fügt ein \ vor ', " etc. ein
        return trim($this->pdo->quote($txt));
    }

    function error()
    {
        return $this->pdo->error;
    }

    function showStatistics()
    {
        $totalTime = $this->microtime_float() - $this->starttime;
        printf("<p><font color=\"#0000ff\">ausgeführte SQL Kommandos: %d\n", $this->sqlcounter);
        printf("<br />Anzahl der übergebenen Zeilen: %d\n", $this->rowcounter);
        printf("<br />Gesamtzeit der queries (MySQL): %f\n", $this->dbtime);
        printf("<br />zeit die PHP brauchte (PHP): %f\n", $totalTime - $this->dbtime);
        printf("<br />Zeit seitdem die DB das letzte mal zurückgesetzt wurde: %f</font></p>\n", $totalTime);
    }

    function resetStatistics()
    {
        $this->sqlcounter = 0;
        $this->rowcounter = 0;
        $this->dbtime = 0;
        $this->starttime = $this->microtime_float();
    }

    private function printsql($sql)
    {
        if ($this->showsql)
            printf("<p><font color=\"#0000ff\">%s</font></p>\n",
                htmlentities($sql));
    }


}