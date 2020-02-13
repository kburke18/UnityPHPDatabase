<?php 

// Created by Kimberly Burke. Summer 2019.
// Simple connection test - can application talk with Cerebro server?

ini_set('display_errors', 'On');
error_reporting(E_ALL);
	// connect to database server
     try {
         $dbh = new PDO('mysql:host=localhost;dbname=name;charset=utf8mb4', 'username', 'password');
    } catch(PDOException $e) {
		echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>';
		header ('offline: true');
    }
?>