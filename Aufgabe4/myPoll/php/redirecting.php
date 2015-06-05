<?php
require_once('pollClass.php');
require_once('webvotefunctions.php');
/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 05.06.15
 * Time: 02:01
 */
include '../template.php';

if (array_item($_POST, "dismiss")) {
    echo '<script>window.location.assign("../index.php")</script>';
    exit;
}
form_start("overViewPolls.php", "", "");

form_back_button("Zurück zur Startseite");
form_button("send", "submit", "zu den Umfragen", "submit");


if ($_GET['title']) {

    form_youtube("https://www.youtube.com/embed/QuoKNZjr8_U");
}
form_end();