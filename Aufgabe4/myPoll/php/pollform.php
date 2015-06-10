<?php /*require_once'../php/polldb.php';
        require_once '../php/pollClass.php';
        require_once '../php/webvotefunctions.php';*/

require_once 'polldb.php';
require_once 'pollClass.php';
require_once 'webvotefunctions.php';

include('../template.php');



$db = new PollDB();

$maxoptions = 3;  // no of options in form
$maxpolls = 1000;   // zähler, wieviele Polls sich maximal in der Datenbank befinden dürfen


$showForm = true;
$formdata = array_item($_POST, "form");
if (is_array($formdata)) {

    // get rid of magic quotes

    if (get_magic_quotes_gpc())

        while ($i = each($formdata))
            $formdata[$i[0]] = stripslashes($i[1]);
    // what to do?

    if (array_item($formdata, "btnSave")) {
        if (validate_data($formdata)) {

            save_data($formdata, $maxoptions, $maxpolls, $db);
            $showform = FALSE;
        }
    } elseif (array_item($formdata, "btnDismiss")) {
        $db->close();
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'content.php';
        header("Location: http://$host$uri/$extra");
        exit;
    }

    if (array_item($_POST, "form") && !array_item($_POST, "dismiss")) {
        $item = array_item($_POST['form'], "title");
        echo '<script>window.location.assign("redirecting.php" )</script>';
        exit;
    } elseif (array_item($_POST, "dismiss")) {
        echo '<script>window.location.assign("../index.php")</script>';
        exit;
    }



}





if ($showForm) {
    build_form($formdata, $maxoptions);
}

html_end();
exit;


//------------------Hier kommen die Funktionen hin------------------------------------------
//erstellt eine Form für Eingabe / Editieren
function build_form($formdata = FALSE, $maxoptions)
{
    //anfang der Form
    form_start("pollform.php", "Neue Umfrage... ", "bitte mind. zwei Auswahlmöglichkeiten anbieten ");
    form_heading_start();
    form_text("Titel", "title", array_item($formdata, "title"), "text");//TODO - Übergabe vom titel
    form_text("Frage:", "description", array_item($formdata, "description"), "text");
    form_heading_end();
    form_panel_body_start();
    $x = "A";
    for ($it = 1; $it <= $maxoptions; $it++) {
        form_text("Antwort $x :", "option$it", array_item($formdata, "option$it"), "text");
        $x++;
    }

    form_panel_body_end();
    form_panel_footer_start();
    form_back_button("Verwerfen");
    form_button("btnSave", "success", "senden", "submit");
    form_panel_footer_end();

    //Schliessen der Form
    form_end();
}

function validate_data($formdata)
{

    $result = TRUE;
    if (trim($formdata["title"]) == "") {
        show_error_msg("You must specify the heading (title)!");
        $result = FALSE;
    }
    if (trim($formdata["description"]) == "") {
        show_error_msg("You must specify the description (query)!");
        $result = FALSE;
    }
    if ($formdata["type"] == "none") {
        show_error_msg("You must choose the question type (option or checkbox)!");
        $result = FALSE;
    }
    if (trim($formdata["option1"]) == "" || trim($formdata["option2"]) == "") {
        show_error_msg("You must specify at least two options!");
        $result = FALSE;
    }
    return $result;
}

// save input data
function save_data($formdata, $maxoptions, $maxpolls, $db)
{

    // save question
    try {
        $sql = "INSERT IGNORE INTO poll_form (title, description) " .
            "VALUES (" .
            $db->sql_string($formdata["title"]) . "," .
            $db->sql_string($formdata["description"]) . ")
             ON DUPLICATE KEY UPDATE title = VALUES(title), description = Values(description )";
        $db->execute($sql);
        $pollID = $db->insertID();

        // save options
        for ($i = 1; $i <= $maxoptions; $i++)
            if (trim($formdata["option$i"]) != "") {
                $sql = "INSERT INTO pollAnswers (pollID, ansText) " .
                    "VALUES ($pollID, " .
                    $db->sql_string($formdata["option$i"]) . ")";
                $db->execute($sql);
            }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    //TODO ---- Umleitug zur Voteanzeige


    // delete old data; leave only $max questions;
    // data in other tables is automatically deleted as well
    // because of foreign key rules ON DELETE CASCADE
    $sql = "SELECT COUNT(*) FROM poll_form";
    $n = $db->querySingleItem($sql);
    if ($n > $maxpolls) {
        $sql = "DELETE FROM poll_form ORDER BY ts LIMIT " . ($n - $maxpolls);
        $db->execute($sql);
        echo "<p>Old poll data has been deleted.</p>\n";
    }
}

?>



