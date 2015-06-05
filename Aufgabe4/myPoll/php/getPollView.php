<?php require_once('polldb.php');
require_once('webvotefunctions.php');
require_once('pollClass.php');
require_once('../template.php');


/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 31.05.15
 * Time: 04:15
 */


$db = new PollDB();



if (isset($_COOKIE['userVoted'])) {
    $user_known = 1;
    $user_ID = $_COOKIE['userVoted'];
} else {
    $user_known = 0;
    $user_ID = mt_rand(1, 10000000);
    setcookie('userVoted', $user_ID, time() + 365 * 24 * 60 * 60); /// Setzt die Gültigkeit des cookies auf 365 Tage
}


if (array_item($_POST, "form")) {
    $pollID = array_item($_POST['form'], "pollID");
    $votes = array_item($_POST, "answer");
    // save data, goto result page
    if ($pollID && is_array($votes) && count($votes) > 0) {
        save_vote($pollID, $votes, $user_known, $user_ID, $db);
    }

}
$tmp = array_item($_POST['form'], "btnResult");
if (($user_known == 1 && array_item($_POST['form'], "btnResult")) || ($user_known == 0 && array_item($_POST['form'], "btnResult"))) {
    echo '<script>window.location.assign("result.php")</script>';
    exit;
}


//--------------Form aufbauen -----------

list_polls_start();
show_poll_list_view($db);
list_polls_end();

build_vote_Form($db, $user_known);
html_end();
exit;

//--------------Funktionen-----------


// save data, goto result page (no output sent yet)
function save_vote($pollID, $votes, $user_known, $user_ID, $db)
{
    $remote_ip = $_SERVER['REMOTE_ADDR'];

    // [to test vote.php, comment out the following lines]

    // try to avoid multiple votes
    // because of foreign key rule with ON DELETE CASCADE,
    // all votedetails get deleted automatically!
    if ($user_known == 1) {
        // delete vote from known user for this poll
        $sql = "DELETE FROM votes " .
            "WHERE pollID=$pollID AND cookie=$user_ID " .
            "LIMIT 1";
        $db->execute($sql);
    } else {
        // delete last vote if there is one
        // within this hour from an unknown user with same IP
        $sql = "DELETE FROM votes " .
            "WHERE pollID=$pollID " .
            "  AND userknown=0 " .
            "  AND ip='$remote_ip' " .
            "  AND ts > DATE_SUB(NOW(), INTERVAL 1 HOUR) " .
            "ORDER BY ts DESC " .
            "LIMIT 1";
        $db->execute($sql);
    }

    // [end uncomment]

    // save data
    $sql = "INSERT INTO votes (pollID, ip, cookie, userknown) " .
        "VALUES($pollID, '$remote_ip', $user_ID, $user_known)";
    $db->execute($sql);
    $voteID = $db->insertID();
    // save vote details
    foreach ($votes as $answerID) {
        $sql = "INSERT INTO votedetails (voteID, answerID) " .
            "VALUES ($voteID, $answerID)";
        $db->execute($sql);
    }

    exit;

}

// show form
function build_vote_form($db, $user_known)
{
    // get current poll


    $tmp_pollID = base64_decode($_GET['title']);
    // show poll heading and description
    form_start("result.php", getHead($db, "title", $tmp_pollID)->title, "");
    form_heading_start();

    form_label(getHead($db, "description", $tmp_pollID)->description, "Frage:");
    form_heading_end();
    form_panel_body_start();
    form_content(getAnswer_Result($db, $tmp_pollID), $user_known);
    form_panel_body_end();
    form_panel_footer_start();
    form_button("btnResult", "btn btn-info", "ohne Wahl ergebnisse anzeigen", "submit", "#");
    form_button("btnVote", "btn btn-success", "Stimme abgeben", "submit", "#");
    form_text("", "pollID", "$tmp_pollID", "hidden");
    form_panel_footer_end();
    form_end();


}

function getHead($db, $what_2_select, $condition)
{

    $sql = "SELECT $what_2_select FROM poll_form " .
        "WHERE pollID='$condition' ";
    $rows = $db->queryObjectArray($sql);
    if ($rows == FALSE || count($rows) != 1) {
        echo "<p class='center-block'>Es gibt leider noch keine Umfragen.</p>\n";
        return;
    }
    return $poll = $rows[0];
}

function getAnswer_Result($db, $poll)
{
    $sql = "SELECT answerID, ansText FROM pollAnswers " .
        "WHERE pollID='$poll' " .
        "ORDER BY ansText";
    return $rows = $db->queryObjectArray($sql);
}

function form_content($rows, $user_known)
{


    // show form
    $x = A;
    foreach ($rows as $row)
        if ($user_known == 1)
            form_answer("answers[$row->answerID]", $row->answerID, $row->ansText, "radio", $x++);
        else
            form_answer("answer[radio]", $row->answerID, $row->ansText, "radio", $x++);

    //processbars werden hier angezeigt
    foreach ($rows as $row) {

    }

    echo "</p>\n";


}


?>