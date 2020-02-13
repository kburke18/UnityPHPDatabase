<?php

// Created by Kimberly Burke. Summer 2019.
// Receives player ID, player name and player team from Unity and saves to PlayerInfo database table under the player ID key

ini_set('display_errors', 'On');
error_reporting(E_ALL);
	// connect to database server
     try {
         $dbh = new PDO('mysql:host=localhost;dbname=name;charset=utf8mb4', 'username', 'password');
    } catch(PDOException $e) {
        echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }

	// set variable for player id
	if (isset($_POST['player_ID'])) {
		$id = $_POST['player_ID'];
	} else {
		$id = "";
	}

	// set variable for player name
	if (isset($_POST['player_name'])) {
		$name = $_POST['player_name'];
	} else {
		$name = "";
	}
	
	// set variable for player team name
    if (isset($_POST['player_team'])) {
		$team = $_POST['player_team'];
	} else {
	  $team = "N/A"; // else store as no affiliation
	}
	
	$idchecker = $dbh->query("SELECT count(*) FROM `PlayerInfo` WHERE player_id = '$id'")->fetchColumn();
	if ($idchecker == 0)
	{
		$sth = $dbh->prepare("INSERT INTO `PlayerInfo` (player_id,player_name,player_team) VALUES (:player_id,:player_name,:player_team)");
		$sth->bindParam(':player_id', $id);
		$sth->bindParam(':player_name', $name);
		$sth->bindParam(':player_team', $team);	
		$result = $sth->execute();
	  
		if ($result){
			echo "Created new user";
			header('userAdded: true'); 	
		}else{
            echo "Created user failed";
			var_dump($sth->errorInfo());
        }
	}
	else
	{
	   
		$sth = $dbh->prepare("UPDATE `PlayerInfo` SET player_star = 0, player_name = (:name), player_team = (:team) WHERE player_id = (:id)");	
		
		$sth->bindParam(':name', $name);	
		$sth->bindParam(':team', $team);	
		$sth->bindParam(':id', $id);
		$result = $sth->execute();

	
	   if($result){
	      echo "Updated player!";
		  header('userUpdated: true');
	   }else{
	      echo "Updated player failed!";
		  var_dump($sth->errorInfo());

	   }
	}
	
	/*
	$teamchecker = $dbh->query("SELECT count(*) FROM `Team` WHERE team_name = '$team'" )->fetchColumn();
		
	if ($teamchecker == 0){
		$teamsql = $dbh->prepare("INSERT INTO `Team` (team_name) VALUES (:team_name)");
		$teamsql->bindParam(':team_name', $team);
		$teamResult = $teamsql->execute();
		if($teamResult){
				//header('teamAdded: true');
		}
		else{
			echo $sth->errorInfo;
			var_dump($sth->errorInfo());
		}
		
	}
	*/	
	
	// DISCONNECT
	$dbh = null;
?>