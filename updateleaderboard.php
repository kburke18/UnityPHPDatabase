<?php 

// Created by Cindy Shi. Summer 2019.
// Receives player ID, player team and earned stars from Untiy and adds earned stars to Team database table under the corresponding player team key

ini_set('display_errors', 'On');
error_reporting(E_ALL);
	// connect to database server
     try {
         $dbh = new PDO('mysql:host=localhost;dbname=name;charset=utf8mb4', 'username', 'password');
    } catch(PDOException $e) {
        echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }

	// set variable for player name
	if (isset($_POST['player_id'])) {
		$id = $_POST['player_id'];
	} else {
		$id = "";
	}
	
	// set variable for player team name
    if (isset($_POST['player_team'])) {
		$team = $_POST['player_team'];
	} else {
	  $team = "N/A"; // else store as no affiliation
	}
	
	// set variable for earned stars
	if (isset($_POST['earnedStars'])) {
		$stars = $_POST['earnedStars'];
	} else {
	  $stars = 0; // else store as 0
	}
	
	//updating Player Info and Team score	
    $sth = $dbh->prepare("UPDATE `PlayerInfo` player JOIN `Team` team ON (player.player_team = team.team_name) SET player.player_star = (player.player_star + (:stars)), team.team_star = (team.team_star + (:stars)) WHERE player.player_id = (:id)");	
    //$sth = $dbh->prepare("UPDATE `PlayerInfo` JOIN `Team` ON (`PlayerInfo`.player_team = `Team`.team_name) SET player_star = (player_star + (:stars)), team_star = (team_star + (:stars)) WHERE player_id = (:id)");	
	$sth->bindParam(':id', $id);
	$sth->bindParam(':stars', $stars);	
	
	//alternative syntax
	//$sth = $dbh->prepare("UPDATE `PlayerInfo` SET player_star = (player_star + ?) WHERE player_id = ?");	
	//$sth->bindParam(1, $stars);
	//$sth->bindParam(2, $id);
	
	$result= $sth->execute();
	if($result) {
				echo "Updating new score";
				header('leaderboardUpdated: true'); 	
				
	}else{
                echo "Updating new score failed";
				var_dump($sth->errorInfo());
	}
	
	// DISCONNECT
	$dbh = null;
?>