<?php

require_once('../php/polldb.php');

$db = new PollDB();

$sql = "DELETE FROM poll_form USING poll_form, poll_form as vtable WHERE (poll_form.title = vtable.title)
        AND (poll_form.description = vtable.description) AND  (NOT poll_form.pollID = vtable.pollID)";

$db->execute($sql);
