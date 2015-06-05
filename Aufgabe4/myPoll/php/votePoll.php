<?php

require_once 'mydb.php';
require_once 'webvotefunctions.php';

// connect to MySQL; show error message (complete HMTL page)
// and exit if no connection
$db = new MyDb();

// set cookie to avoid multiple votes
if (isset($_COOKIE['webvoteuser'])) {
    $user_known = 1;
    $user_ID = $_COOKIE['webvoteuser'];
} else {
    // maybe cookies are disabled
    $user_known = 0;
    $user_ID = mt_rand(1, 1000000000);
    // cookie valid for one year
    setcookie('webvoteuser', $user_ID, time() + 365 * 24 * 60 * 60);
}

// process form data
if (array_item($_POST, "btnVote")) {
    $pollID = array_item($_POST, "pollID");
    $votes = array_item($_POST, "options");
    // save data, goto result page
    if ($pollID && is_array($votes) && count($votes) > 0)
        save_vote($pollID, $votes, $user_known, $user_ID, $db);
}

// show page
html_start("W�hlen Sie!");
show_vote_form($db);
html_end();
exit;

// ----------- functions ---------

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
    foreach ($votes as $optID) {
        $sql = "INSERT INTO votedetails (voteID, optID) " .
            "VALUES ($voteID, $optID)";
        $db->execute($sql);
    }
    // go to http://<currenthostname>/<currentdirectory>/result.php
    $baseurl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    header("Location: $baseurl/result.php");
    exit;
}

// show form
function show_vote_form($db)
{
    // get current poll
    $sql = "SELECT pollID, heading, description, polltype " .
        "FROM polls ORDER BY ts DESC LIMIT 1";
    $rows = $db->queryObjectArray($sql);
    if ($rows == FALSE || count($rows) != 1) {
        echo "<p>Es gibt leider noch keine Umfragen.</p>\n";
        return;
    }
    $poll = $rows[0];

    // show poll heading and description
    echo "<h2>", htmlentities($poll->heading), "</h2>\n",
    "<p>", htmlentities($poll->description), "</p>\n";

    // get options
    $sql = "SELECT optID, optText FROM polloptions " .
        "WHERE pollID=" . $poll->pollID . " " .
        "ORDER BY optText";
    $rows = $db->queryObjectArray($sql);

    // show form
    echo '<form method="post" action="vote.php"><p>', "\n";
    foreach ($rows as $row)
        if ($poll->polltype == 'checkbox')
            printf('<br /><input type="checkbox" name="options[%s]" value="%s">%s</input>' . "\n",
                $row->optID, $row->optID, $row->optText);
        else
            printf('<br /><input type="radio" name="options[radio]" value="%s">%s</input>' . "\n",
                $row->optID, $row->optText);

    echo "</p>\n";
    echo '<p><input type="submit" value="W�hlen!" name="btnVote"></p>', "\n";
    echo '<input type="hidden" name="pollID" value="',
    $poll->pollID, '">', "\n";
    echo '</form>', "\n";

    // link to results
    echo '<p><a href="result.php">Direkt zu den Ergebnissen ...</a></p>', "\n";
}

?>
