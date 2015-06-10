<?php
/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 31.05.15
 * Time: 02:20
 */

require_once("polldb.php");
include_once("../template.php");
$db = new PollDB();



require_once("pollClass.php");

list_polls_start();
show_poll_list_view($db);
list_polls_end();



// --------Funktionen --------


