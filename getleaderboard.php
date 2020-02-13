<?php

// Created by Cindy Shi. Summer 21019.
// Receives request from Unity and returns all data in Team database table encoded as JSON.

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "dbname";

    if(isset($_POST['request']) && $_POST['request'] =='getleaderboard' )
    {
		// Create connection
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		
		$sql = 'SELECT * FROM Team ORDER BY team_star DESC';
		
		$teamList = array();
		foreach ($conn->query($sql) as $data) {
			$teamList[] = array(
			'teamName' => $data['team_name'],
			'teamStars' => $data['team_star'],
			);
		}

		echo json_encode(array('teamList' => $teamList));

		$conn = null;
	}
?>
