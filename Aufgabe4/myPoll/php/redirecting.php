<?php
require_once('pollClass.php');
require_once('webvotefunctions.php');
/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 05.06.15
 * Time: 02:01
 */
$baseurl = $_SERVER['HTTP_HOST']."template.php";
include ('../template.php');

if (array_item($_POST['form'], "dismiss")) {
    echo '<script>window.location.assign("../index.php")</script>';
    exit;
}elseif(array_item($_POST['form'],"send")){
    echo '<script>window.location.assign("overViewPolls.php?")</script>';
    exit;
}
form_start("redirecting.php", "", "");

form_button("dismiss","submit","Zurück zur Startseite", "submit");
form_button("send", "submit", "zu den Umfragen", "submit");

form_youtube("https://www.youtube.com/embed/QuoKNZjr8_U");

form_end();