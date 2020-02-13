<?php 

// Created by Kimberly Burke. Summer 2019.
// Receives account name, password, confirmation password, and email from Unity and saves to the Player database table

ini_set('display_errors', 'On');
error_reporting(E_ALL);
	// connect to database server
    try {
         $dbh = new PDO('mysql:host=localhost;dbname=name;charset=utf8mb4', 'username', 'password');
    } catch(PDOException $e) {
        echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>\n';
    }
	
	$valid = true; // Validity checker

	// set variable for account name
	if (isset($_POST['account_name'])) {
		$name = $_POST['account_name'];
	} else {
		$name = "";
	}
	
	// set variable for account password
	if (isset($_POST['account_pass'])) {
		$pass = $_POST['account_pass'];
	} else {
		$pass = "";
	}
	
	// set variable for confirming password
	if (isset($_POST['confirm_pass'])) {
		$confirm = $_POST['confirm_pass'];
	} else {
		$confirm = "";
	}
	
	// set variable for account email
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
	} else {
		$email = "";
	}
	
	// Validate e-mail
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo("$email is a valid email address\n");
	} else {
		echo("$email is not a valid email address\n");
		header('EmailFormat: false'); // error for email format
		$valid = false;
	}
	
	// Validate that passwords match
	if ($pass === $confirm) {
		echo("Password confirmation matches.\n");
	} else {
		echo("Password confirmation does NOT match.\n");
		header('PasswordMatch: false');
		$valid = false;
	}
	
	// Check if username or email already exists in database
	// !uniquechecker is not case sensitive!
	$uniquechecker = $dbh->query("SELECT count(*) FROM `Player` WHERE account_name = '$name' OR account_email = '$email'")->fetchColumn();
	echo $uniquechecker;

	if ($uniquechecker == 0 && $valid == true) {
		header('UserCreation: true');
		$hashpw = password_hash($pass, PASSWORD_DEFAULT);
		
		$sth = $dbh->prepare("INSERT INTO Player (player_id,account_name,account_pass,account_email) VALUES (:id,:name,:password,:email)");
		
		$sth->bindParam(':id', $id);
		$sth->bindParam(':name', $name);
		$sth->bindParam(':password', $hashpw);
		$sth->bindParam(':email', $email);
		
		$sth->execute();
		
		// Returning the player ID created by new account
		$sql = "SELECT player_id FROM Player WHERE account_name = '$name'";  // Setup the query
		$query = $dbh->query($sql); 						   // Run query in db
		$result = $query->fetch(PDO::FETCH_ASSOC);  		   // Fetch the data
		echo $result['player_id']; 
	} else {
		header('UserCreation: false');
		// Define the error
		$uniquename = $dbh->query("SELECT count(*) FROM `Player` WHERE account_name = '$name'")->fetchColumn();
		if ($uniquename != 0) {
			header('AccountFail: true');
		}
		$uniqueemail = $dbh->query("SELECT count(*) FROM `Player` WHERE account_email = '$email'")->fetchColumn();
		if ($uniqueemail != 0) {
			header('EmailFail: true');
		}
	}
	
	$dbh = null; // DISCONNECT	
?>