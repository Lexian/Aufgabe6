<?php require_once('config.php');

/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 31.05.15
 * Time: 02:21
 */



try {
    $connection = new PDO($dsn, $db_usr, $db_pass);
    $connection->exec("SET CHARACTER SET utf8");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = $connection->prepare('SELECT * FROM poll_form');
    $sql->execute();
    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        // schreibt alle werte der Tabelle in ein Array, welches dann and die View weitergeleitet wird
        $mixed = array($row['id'], $row['title'], $row['question'], $row['answerA'], $row['answerB']
        , $row['answerC'], $row['answerD'], $row['answerE']);

        $text = json_encode($mixed);
        //damit die Url nicht so leserlich is ;)
        $request_text = urlencode($text);
        $data = base64_encode($request_text);
        echo "<a href=\"getPollView.php?row=$data\" class=\"list-group-item  \">" . $row['id'] . '.) ' . $row['title'] . '</a>';

    }


} catch (PDOException $e) {
    echo 'geht nicht aauf: ' . $e->getMessage();
}



?>
