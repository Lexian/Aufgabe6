<?php

require_once 'polldb.php';
require_once 'webvotefunctions.php';
require_once 'pollClass.php';
require_once '../template.php';

// connect to MySQL; show error message (complete HMTL page)
// and exit if no connection


// start page
$db = new PollDB();
$test =$_POST;
// retrieve poll data
if ($pollID = base64_decode(array_item($_REQUEST, 'pollID'))) {
    $sql = "SELECT pollID, title, description " .
        "FROM poll_form WHERE pollID=$pollID";
    $rows = $db->queryObjectArray($sql);
}elseif($csv_pollID = array_item($_REQUEST,'createCVS')){
    downloadCSV($db,$csv_pollID);
    $pollID_enc =  base64_encode($csv_pollID);// TODO pollid übergeben
    echo '<script>window.location.replace("result.php?pollID='.$pollID_enc.'")</script>';
    exit;
}

// is pollID valid?
if (!$pollID || $rows == FALSE || count($rows) != 1) {
    // pollID was invalid or not set
    // get current poll
    $sql = "SELECT pollID, title, description " .
        "FROM poll_form ORDER BY ts DESC LIMIT 1";
    $rows = $db->queryObjectArray($sql);
    if ($rows == FALSE || count($rows) != 1) {
        echo "<p>Es gibt leider noch keine Umfragen.</p>\n";
        html_end();
        exit;
    }
}

// ------------ Form aufbauen----------------


list_polls_start();
show_poll_list_result($db);
list_polls_end();

form_start("result.php", "Ergebnis:", "");
show_poll_results($rows[0]->pollID, $rows[0]->title,
    $rows[0]->description, $db);
form_end();
// $db->showStatistics();
// echo '<p><a href="vote.php">vote.php</a></p>', "\n";

// end page
html_end();
exit;


// ------------ Funktionen ----------------

// show table with poll results
function show_poll_results($pollID, $title, $description, $db)
{
    // get no of votes
    $sql = "SELECT COUNT(*) FROM votes WHERE pollID=$pollID";
    $noOfVotes = $db->querySingleItem($sql);


    // get poll results
    $sql = "SELECT pollAnswers.ansText, " .
        "       COUNT(votedetails.voteID) AS cnt " .
        "FROM pollAnswers LEFT JOIN votedetails USING (answerID) " .
        "WHERE pollAnswers.pollID=$pollID " .
        "GROUP BY pollAnswers.answerID " ;
    $rows = $db->queryObjectArray($sql);

    // show results
    form_heading_start();
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    copyfield($actual_link);
    echo "<h2>Frage:</h2>\n",
    "<p>", htmlentities($description), "</p>\n";

    form_heading_end();
    form_panel_body_start();
    $x = A;
    if ($noOfVotes >= 5) {
        foreach ($rows as $row) {
            $percent = 100.0 / $noOfVotes * $row->cnt;
            $res_in_percent = (round($percent) * 2);
            echo 'Antwort', $x++, ': ', $row->ansText;
            echo '<div class="progress progress-striped active" >';
            echo ' <div class="progress-bar progress-bar-danger" role="progressbar" '
            , html_attribute("aria-valuenow", $res_in_percent),
            html_attribute("value", $res_in_percent),
            html_attribute("aria-valuemin", "0"),
            html_attribute("aria-valuemax", "100"),
            html_attribute("style", "width:$percent%"), '>';
            echo '<p>'.$row->cnt.' Stimmen</p>';
            echo '</div>';
            echo '</div>';
        }

        echo '<hr />';
        echo "<p>An dieser Umfrage haben sich bisher $noOfVotes ",
        "Personen beteiligt. ";
    } else {
        foreach ($rows as $row) {
            $percent = (100.0 / $noOfVotes) * $row->cnt;
            echo 'Antwort', $x++, ': ', $row->ansText;
            echo '<div class="progress progress-striped active">';
            echo ' <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'">';
            echo '<span class="sr-only">60% Complete (success)</span>';
            echo '</div>';
            echo '</div>';
        }

        echo '<hr />';
        echo "<p><strong>Derzeit ist noch keine Auswertung möglich da erst $noOfVotes Personen gewählt haben. eine Auswertung findet erst bei 5 Stimmen statt.</strong> ";
    }
    form_panel_body_end();
    form_panel_footer_start();

    echo '<input  type="submit" class="btn btn-warning" name="createCVS" value="'.$pollID.'"">
    <span class="glyphicon glyphicon-icon glyphicon-download">CSV</span>';
    echo '</input>  ', "\n";

    form_panel_footer_end();
    echo "</p>\n";
}


function show_other_polls($pollID, $db)
{
    // only makes sense if there is more than one poll
    $sql = "SELECT COUNT(*) FROM poll_form";
    if ($db->querySingleItem($sql) > 1) {
        echo "<p>Sehen Sie sich auch die Ergebnisse anderer Umfragen an!</p>\n<ul>";
        $sql = "SELECT pollID, heading FROM poll_form ORDER BY ts DESC";
        $rows = $db->queryObjectArray($sql);
        foreach ($rows as $row) {
            // don't show link to current poll
            if ($row->pollID != $pollID)
                printf('<li><a href="result.php?pollID=%d">%s</a></li>' . "\n",
                    $row->pollID, htmlentities($row->title));
        }
        echo "</ul>\n";
    }
}

function downloadCSV($db, $pollid)
{
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$pollid.csv");
    header("Pragma: no-cache");
    header("Expires: 0");


    $output = fopen("$pollid.csv", w);


    $sql = 'SELECT  poll_form.title, poll_form.description, pollAnswers.ansText,
          (select count(*) from votedetails where votedetails.answerID = pollAnswers.answerID)
          FROM poll_form Left JOIN pollAnswers ON poll_form.pollID = pollAnswers.pollID
          Left JOIN votes ON poll_form.pollID = votes.pollID
          Left Join votedetails on votes.voteID = votedetails.voteID and pollAnswers.answerID = votedetails.answerID
          WHERE poll_form.pollID='.$pollid.' group by poll_form.title, poll_form.description, pollAnswers.ansText';
    $rows = $db->queryArray($sql);
    foreach ($rows as $line) {
        fputcsv($output, $line);
    }
    fclose($output);

}
function get_vote_detailed(){

}

?>