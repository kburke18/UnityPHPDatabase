<?php

// Created by Kimberly Burke. Summer 2019.
// Receives player ID from Unity and retrieves all level data with matching player ID key from PlayerLevel database table 
// and all player information from PlayerInfo database table that matches player ID key

ini_set('display_errors', 'On');
error_reporting(E_ALL);

	$servername = "localhost";
	$username = "username";
	$password = "password";
	$dbname = "dnname";
	
	// set variable for player id
	if (isset($_POST['player_ID'])) {
		$id = $_POST['player_ID'];
	} else {
		$id = "";
	}

    if(isset($_POST['request']) && $_POST['request'] =='getlevels')
    {
		// Create connection
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		
		// Retrieve all level information for the player
		$sql = "SELECT * FROM PlayerLevel WHERE player_id = '$id'";
		$levelList = array();
		foreach ($conn->query($sql) as $data) {
			$levelList[] = array(
				'objectiveName' => $data['level_objective'],
				'score' => $data['level_score'],
				'star' => $data['level_stars'],
				'chapter' => $data['level_chapter'],
				'mode' => $data['level_mode'],
				'difficulty' => $data['level_difficulty'],
			);
		};
		// Pass level information to Unity as a JSON of LevelData
		echo json_encode(array('levelList' => $levelList)) . "\n";
		if (count($levelList) > 0) {
			header ('LevelsSucceed: true');
		}

		$sql = "SELECT * FROM PlayerInfo WHERE player_id = '$id'";
		$query = $conn->query($sql);
		$playerArr = array();
		foreach ($conn->query($sql) as $data) {
			$playerArr[] = array(
				'playerName' => $data['player_name'],
				'playerStars' => $data['player_star'],
				'playerTeam' => $data['player_team'],
				'playerMoney' => $data['player_money'],
				'progress1' => $data['progress1'],
				'progress2' => $data['progress2'],
				'progress3' => $data['progress3'],
			);
		}
		echo json_encode($playerArr) . "\n";
		if (count($playerArr) > 0) {
			header ('PlayerSucceed: true');
		}
		$conn = null;
	}
?>
