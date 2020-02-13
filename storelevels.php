<?php 

// Created by Kimberly Burke. Summer 2019.
// Receives player ID, level name, level chapter, level mode, level difficulty, level score and level star and saves to PlayerLevel
// database table under the player ID key

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

	// set variable for level name
	if (isset($_POST['level_name'])) {
		$name = $_POST['level_name'];
	} else {
		$name = "N/A";
	}
	
	// set variable for level chapter
	if (isset($_POST['level_chapter'])) {
		$chapter = $_POST['level_chapter'];
	} else {
		$chapter = "N/A";
	}
	
	// set variable for level mode
	if (isset($_POST['level_mode'])) {
		$mode = $_POST['level_mode'];
	} else {
		$mode = "N/A";
	}
	
	// set variable for level difficulty
	if (isset($_POST['level_difficulty'])) {
		$difficulty = $_POST['level_difficulty'];
	} else {
		$difficulty = "N/A";
	}
	
	// set variable for level score
	if (isset($_POST['level_score'])) {
		$score = $_POST['level_score'];
	} else {
		$score = 0;
	}

	$levelexists = $dbh->query("SELECT count(*) FROM `PlayerLevel` WHERE player_id = '$id' AND level_objective = '$name' AND level_chapter = '$chapter' AND level_difficulty = '$difficulty' AND level_mode = '$mode'")->fetchColumn();
	if ($levelexists == 0)
	{
		// set variable for level stars
		if (isset($_POST['level_star'])) {
			$star = $_POST['level_star'];
		} else {
			$star = 0;
		}
		
		// create new entry for the level
		$sth = $dbh->prepare("INSERT INTO `PlayerLevel` (player_id,level_objective,level_chapter,level_difficulty,level_mode,level_score,level_stars) VALUES (:player_id,:level_objective,:level_chapter,:level_difficulty,:level_mode,:level_score,:level_star)");
		$sth->bindParam(':player_id', $id);
		$sth->bindParam(':level_objective', $name);
		$sth->bindParam(':level_chapter', $chapter);
		$sth->bindParam(':level_difficulty', $difficulty);
		$sth->bindParam(':level_mode', $mode);
		$sth->bindParam(':level_score', $score);	
		$sth->bindParam(':level_star', $star);
		
		$result = $sth->execute();
	  
		if ($result){
			echo "Created new level entry";
			header('levelAdded: true'); 	
		}else{
            echo "Created level failed";
			var_dump($sth->errorInfo());
        }
	}
	else
	{
		// set variable for level stars
		if (isset($_POST['level_star'])) {
			$star = $_POST['level_star'];
		} else {
			$star = $levelexists['level_stars'];
		}
		
		// udpate the existing level entry
		$sth = $dbh->prepare("UPDATE `PlayerLevel` SET level_score = (:level_score), level_stars = (:level_star) WHERE player_id = (:player_id) AND level_objective = (:level_objective) AND level_chapter = (:level_chapter) AND level_difficulty = (:level_difficulty)  AND level_mode = (:level_mode)");	
		
		$sth->bindParam(':player_id', $id);
		$sth->bindParam(':level_objective', $name);
		$sth->bindParam(':level_chapter', $chapter);
		$sth->bindParam(':level_difficulty', $difficulty);
		$sth->bindParam(':level_mode', $mode);
		$sth->bindParam(':level_score', $score);	
		$sth->bindParam(':level_star', $star);
		
		$result = $sth->execute();
	  
		if ($result){
			echo "Updated level entry";
			header('levelUpdated: true'); 	
		}else{
            echo "Updated level failed";
			var_dump($sth->errorInfo());
        }
	}
	
	// DISCONNECT
	$dbh = null;
?>