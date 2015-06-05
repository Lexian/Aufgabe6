<?php require_once('password.php');
/**
 * Created by PhpStorm
 * User: daniel junker
 * Date: 30.05.2015
 * Time: 19:21
 */

try {

    // Chevron 7 aktiviert, Das portal öffnet sich :D
    $pdo = new PDO("mysql:host=$db_host", base64_decode($db_usr), base64_decode($db_pass));
    //Oh Oh, gugge wir mal ob die Iris zu is
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Error Handling

    $pdo->query("CREATE DATABASE IF NOT EXISTS $db_name");
    $pdo->query("use $db_name");


    echo "Verbindung hergestellt";
    $q = "


    CREATE TABLE IF NOT EXISTS poll_form(
    pollID INTEGER NOT NULL AUTO_INCREMENT,
    ts TIMESTAMP,
    title VARCHAR(100) NOT NULL ,
    description LONGTEXT,
     PRIMARY KEY (pollID)
    )ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;

    CREATE TABLE pollAnswers (
answerID INT(11) NOT NULL AUTO_INCREMENT,
  ansText VARCHAR(100) COLLATE latin1_german1_ci NOT NULL DEFAULT '',
  pollID INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (answerID),
  KEY pollID (pollID),
  CONSTRAINT polloptions_ibfk_1 FOREIGN KEY (pollID) REFERENCES poll_form (pollID) ON DELETE CASCADE
)ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE votes (
  voteID INT(11) NOT NULL AUTO_INCREMENT,
  ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  pollID INT(11) NOT NULL DEFAULT '0',
  ip VARCHAR(40) COLLATE utf8_general_ci DEFAULT NULL,
  cookie INT(11) DEFAULT NULL,
  userknown TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (voteID),
  KEY pollID (pollID),
  CONSTRAINT votes_ibfk_1 FOREIGN KEY (pollID) REFERENCES poll_form (pollID) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6781 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE votedetails (
  voteID INT(11) NOT NULL DEFAULT '0',
  answerID INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (voteID,answerID),
  KEY answerID (answerID),
  CONSTRAINT votedetails_ibfk_13 FOREIGN KEY (voteID) REFERENCES votes (voteID) ON DELETE CASCADE,
  CONSTRAINT  votedetails_ibfk_14 FOREIGN KEY (answerID) REFERENCES pollAnswers (answerID) ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
";

    $pdo->exec($q) or die(print_r($dbh->errorInfo(), true));

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
