<?php
	/**
	 * this page fetches the most recent articles reguarding
	 * user-preferred municipalities.
	 */

	session_start();

	require "dbUtils.php";
	require "Article.php";

	//if($_SERVER["REQUEST_METHOD"] == "POST") {

		header('Content-Type: Application/json;charset=utf-8');
		//header('Content-Type: text/html;charset=utf-8');

		define("MAX_NO_OF_ARTICLES", 10);

		$idUtente = $_SESSION["IdUtente"];

		$query = "SELECT AR.*, AU.NomeAutore, CO.Nome
				  FROM articoli AR, autori AU, comuni CO
				  WHERE	AR.Comune = CO.IdComune
				   		AND AR.Autore = AU.IdAutore
				  		AND AR.Comune IN (SELECT Comune
				  				   		  FROM preferisce
							   	   		  WHERE Utente = $idUtente)
				  ORDER BY AR.DataPubblicazione DESC
				  LIMIT ".MAX_NO_OF_ARTICLES;

		$qres = mysqli_query($dbconn, $query);

		//echo mysqli_error($dbconn);

		$articles = array();

		while($row = mysqli_fetch_assoc($qres)) {

			// the DB if utf8-encoded, but I still get some encoding errors if I don't do this
			$article = new Article( utf8_encode($row["IdArticolo"]),
									utf8_encode($row["DataPubblicazione"]),
									utf8_encode($row["Titolo"]),
									utf8_encode($row["Testo"]),
									utf8_encode($row["NomeAutore"]),
									utf8_encode($row["Nome"]));
			//echo $article."<br>";
			array_push($articles, $article);
		}

		//var_dump($articles);

		echo json_encode($articles);

		//echo "<h2>".json_last_error()."</h2>";

	//}
?>
