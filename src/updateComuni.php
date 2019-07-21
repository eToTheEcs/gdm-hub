<?php

	require_once 'dbUtils.php';
	session_start();

	if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["comuni"])) {

		$deleteOldQuery = "DELETE
						   FROM preferisce
						   WHERE  Utente = ".$_SESSION['IdUtente'];
		mysqli_query($dbconn, $deleteOldQuery);

		foreach($_POST["comuni"] as $comune) {
			echo $comune."<br>";

			$query = "INSERT INTO preferisce (Utente, Comune, DataPreferenza)
					  VALUES (".$_SESSION['IdUtente'].", $comune, NOW())";
			mysqli_query($dbconn, $query);
		}

		header("Location: accountShow.php");
	}
	else {
		echo "<h1>MISSING DATA</h1>";
	}

?>
