<?php 
ini_set('display_errors', 'On');
error_reporting(E_ALL);

     try {
         $dbh = new PDO('mysql:host=localhost;dbname=name;charset=utf8mb4', 'username', 'password');
    } catch(PDOException $e) {
        echo '<h1>An error has occurred.</h1><pre>', $e->getMessage() ,'</pre>';
    }


	if (isset($_POST['name'])) {
	$name = $_POST['name'];
	}else{
	  $name = 0;
	}

	if (isset($_POST['score'])) {
	$score = $_POST['score'];
	}else{
	  $score = "";
	}

    if (isset($_POST['school'])) {
	$school = $_POST['school'];
	}else{
	  $school = "";
	}
	
	$entrychecker = $dbh->query("SELECT count(*) FROM `Userleaderboard` WHERE name = '$name' AND score < '$score'")->fetchColumn();
	echo "$entrychecker";
	if ($entrychecker == 0)
	{
	  $existschecker = $dbh->query("SELECT count(*) FROM `Userleaderboard` WHERE name = '$name'")->fetchColumn();
	
		if ($existschecker == 0)
		{
			$sth = $dbh->prepare("INSERT INTO `Userleaderboard` (name,score,school) VALUES (:name,:score,:school)");	
			$sth->bindParam(':name', $name);
			$sth->bindParam(':score', $score);
			$sth->bindParam(':school', $school);	
			$result= $sth->execute();
			if($result) {
				echo "Creating new score";
				header('scoreAdded: true'); 	
			}else{
                echo "Creating new failed id".$id."name".$score."score".$school."school";
				var_dump($sth->errorInfo());
			}
        }
		else
		{
		echo "User already exists";
		}
	}	
	else
	{
	   $updatescore = $dbh->query("UPDATE `Userleaderboard` SET score = '$score' WHERE name = '$name' AND school = '$school'");
	   if($updatescore){
	       echo "Updated instance!";
	   }else{
	      echo "Updated instance failed!";
	   }
	}
	$dbh = null;
?>