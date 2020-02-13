<?php 

// Created by Kimberly Burke. Summer 2019.
// Receives account name and password, fetches if account name and password are under a player ID in the Player database table, 
// and returns the player ID.

ini_set('display_errors', 'On');
error_reporting(E_ALL);
	// connect to database server
     try {
         $dbh = new PDO('mysql:host=localhost;dbname=name;charset=utf8mb4', 'username', 'password');
    } catch(PDOException $e) {
        echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>\n';
    }

	$name = $_POST['account_name'];
	$password = $_POST['account_pass'];
	// echo "name:".$name." password:".$password;

	$sql = "SELECT * FROM Player WHERE account_name = '$name'";

	foreach ($dbh->query($sql) as $data) {
		$message=$data['account_pass'];
		$message=$data['player_id'];
		$message=$data['account_name'];
	}
	
	if ($password != '') {
		if (password_verify($password, $data['account_pass'])) {
			echo "success\n";
			header('LoginSucceed: true');
			header('PlayerID: ' . $data['player_id']);
			header('PlayerName: ' . $data['account_name']);
			
			// Returning the player ID created by new account
			$sql = "SELECT player_id FROM Player WHERE account_name = '$name'";  // Setup the query
			$query = $dbh->query($sql); 						   // Run query in db
			$result = $query->fetch(PDO::FETCH_ASSOC);  		   // Fetch the data
			$id = $result['player_id'];
			echo $result['player_id'];
		} else {
			echo "wrong\n";
			echo "dbpassword: ".$data['account_pass']."\n";
			header('LoginSucceed: false');
			header('PasswordFail: true');
		}
	} else {
		echo "wrong\n";
		header('LoginSucceed: false');
	}
	$dbh = null; // DISCONNECT	
?>