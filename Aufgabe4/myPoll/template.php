<?php
/**
 * Created by PhpStorm.
 * User: danieljunker
 * Date: 30.05.15
 * Time: 19:16
 */
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<title>My Voting</title>
		<meta charset="UTF-8">
		<meta name=description content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="/css/basestyle.css" rel="stylesheet" type="text/css">
	</head>
	<body>
    <header>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <a class="navbar-brand" href="#">Umfragenportal</a>
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="/myPoll/">Home</a>
                </li>
                <li>
                    <a href="/myPoll/php/overViewPolls.php">Umfragen ansehen</a>
                </li>
            </ul>
        </nav>


    </header>
    <section class="">
        <div class="jumbotron ">
            <div class="container ">
                <h1>Umfragenportal</h1>
                <p><a href="/myPoll/php/pollform.php" type="submit" name="form[btnLogin]" class="btn btn-default" >Umfrage erstellen</a></p>

            </div>
        </div>
    </section>


