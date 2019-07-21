<?php

	require_once 'dbUtils.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["commentText"]) && !empty($_POST["userId"]) && !empty($_POST["articleId"])) {

		$commentText = trim(htmlspecialchars($_POST["commentText"]));
		$userId = trim($_POST["userId"]);
		$articleId = trim($_POST["articleId"]);

		$query = "INSERT INTO commenti (IdUtente, IdArticolo, DataCommento, TestoCommento)
				  VALUES ($userId, $articleId, NOW(), '$commentText')";
		//echo $query."<br>";
		$qres = mysqli_query($dbconn, $query);

		if($qres == FALSE)
			echo "<h1>CANNOT INSERT ARTICLE: ".mysqli_error($dbconn)."</h1>";
		else
			echo "article correctly inserted";
	}
	else {
		echo "<h1>MISSING DATA</h1>";
	}

?>
