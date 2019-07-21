<?php

	/**
	 * this is the core engine of the website
	 * it browses the source page of the newspaper's website
	 * and extracts interesting data
	 */

	require_once '..\\libs\\simplehtmldom\\simple_html_dom.php';
	require_once 'utils.php';
	require_once "dbUtils.php";
	require_once 'Article.php';

	header('Content-type: text/html;charset=utf-8');

	if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["comune"])) {

		// final data to be stored in the database
		$articleList = array();

		// se non c'è l'ultimo accesso, prendi gli articoli degli ultimi 3 giorni
		if(!isset($_COOKIE["Last_access"])) {
			$lastAccessTimestamp = date("y-m-d\Th:i:s", strtotime("3 days ago"));

		}
		else {
			// === parse last access time and date ===
			$lastAccessTimestamp = $_COOKIE["lastAccess"];
		}
		$dateTimeSplitted = splitDateTime($lastAccessTimestamp, " ");
		$lastAccessDate = $dateTimeSplitted[0]/*"11-05-2019"*/;

		$exploded = explode("-", $lastAccessDate);
		$lastAccessDateObj = date("y-m-d", mktime(0, 0, 0, $exploded[1], $exploded[0], $exploded[2]));
		$lastAccessTime = $dateTimeSplitted[1]/*"17:00:00"*/;

		$municipalityId = trim($_POST["comune"]);

		// resolve the foreign key of the municipality
		$query = "SELECT Nome
				  FROM comuni
				  WHERE IdComune = $municipalityId";
		$municipality = mysqli_fetch_assoc(mysqli_query($dbconn, $query))["Nome"];

		//echo "<h1 style='color: red;'>Date: ".$lastAccessDate.", Time: ".$lastAccessTime."</h1>";

		$currentDate = NULL;

		$stop = false;

		// just... 10 pages is more than enough :)
		for($pageIndex = 0; $pageIndex < 10 && !$stop; $pageIndex++) {

			// === fetch page source ===
			$pageSrc = file_get_html(getSearchUrl(urlencode($municipality), $pageIndex+1));

			//echo "<h1 style='color: green;'>PAGE INDEX: ".($pageIndex+1)."</h1>";

			//echo $pageSrc;

			// === extract data from source ===

			$articleContentList = $pageSrc->find("div.gnn-main-content .entry_content");

			foreach($articleContentList as $article) {

				$articleTimestamp = $article->find(".entry_date", 0)->datetime;
				$dateTimeSplitted = splitDateTime($articleTimestamp, "T");
				$articleDate = $dateTimeSplitted[0];
				$exploded = explode("-", $articleDate);
				$articleDateObj = date("y-m-d", mktime(0, 0, 0, $exploded[1], $exploded[0], $exploded[2]));
				$articleTime = $dateTimeSplitted[1];

				/*echo "<h2>data articolo: ".$articleDate." - ".$articleTime;
				echo "<br>".$articleDateObj.", ".strtotime($lastAccessDateObj);*/

				// stop fetching articles when they are too old
				if(/*strtotime($articleDateObj) < strtotime($lastAccessDateObj)*/$articleDateObj < $lastAccessDateObj) {
					$stop = true;
					//echo "troppo vecchio in data<br>";
					break;
				}
				else if($articleDate == $lastAccessDate && $articleTime < $lastAccessTime) {
					$stop = true;
					//echo "troppo vecchio in orario<br>";
					break;
				}

				$title = filter_var(trim($article->find("h2.entry_title a", 0)->innertext), FILTER_SANITIZE_STRING);

				if(($tmp = $article->find("span.entry_author", 0) != NULL))
					$author = trim($article->find("span.entry_author", 0)->innertext);
				else
					$author = "AA.VV.";

				//echo "<h2>data articolo: ".$articleDate." - ".$articleTime."<br> autore: ".$author."</h2>";

				$articlePage = file_get_html($article->find(".entry_title a", 0)->href);

				// fetch article content
				$content = filter_var(trim($articlePage->find("#article-body", 0)->innertext), FILTER_SANITIZE_STRING);

				// fetch image url
				//$imageUrl = trim($articlePage->find(".entry_media img")->src);

				//echo $content."<br>";

				$content = str_replace("try { MNZ_RICH('Bottom');} catch(e) {}", "", $content);

				//var_dump(str_replace(" try {MNZ_RICH", "", $content));

				//echo $content."<br>";

				/*$end = strpos($content, "Leggi anche");

				$content = substr($content, $end);
				echo $content."<br><br>";*/
				//echo new Article($articleTimestamp, $title, $content, $author);
				//echo new Article(-1, $articleTimestamp, $title, $content, $author, "");
				array_push($articleList, new Article(-1, $articleTimestamp, $title, $content, $author, ""));
			}
		}
		//var_dump($articleContentList);

		// === insert data into the database ===

		// get the municipality ID
		/*$query = "SELECT IdComune
			FROM comuni WHERE Nome = '$municipality'";
		$qres = mysqli_query($dbconn, $query);
		$idComune = mysqli_fetch_assoc($qres)["IdComune"];*/

		$articleCount = 0;

		foreach($articleList as $item) {

			//echo "<h3>CIAO</h3>";

			$itemAuthor = trim($item->getAuthor());
			// no author means many authors (it's quite like communism but...hey, who cares)
			/*if($itemAuthor == "")
				$itemAuthor = "AA.VV.";*/

			// find the author ID for the article
			$query = "SELECT IdAutore
					  FROM autori
					  WHERE NomeAutore = '$itemAuthor'";
			$qres = mysqli_query($dbconn, $query);

			$idAutore = mysqli_fetch_assoc($qres)["IdAutore"];
			$numAuthors = mysqli_affected_rows($dbconn);

			if((int)$numAuthors == 0) {
				/*$exploded = explode("\\s*", $itemAuthor);
				$authorName = $exploded[0];
				if(count($exploded > 1))
					$authorSurname = $exploded[1];
				else
					$authorSurname = "";*/

				$query = "INSERT INTO autori (NomeAutore)
						  VALUES ('$itemAuthor')";
				$qres = mysqli_query($dbconn, $query);
				$idAutore = mysqli_insert_id($dbconn);
			}

			//echo "<h1 style='color: green;'>$idAutore</h1>";

			/*$query = "INSERT INTO articoli (Titolo, Testo, DataPubblicazione, Comune, Autore)
					  VALUES ('".utf8_encode($item->getTitle())."', '".utf8_encode($item->getContent())."', '".utf8_encode($item->getDate())."', $municipalityId, $idAutore)";*/
			$query = "INSERT INTO articoli (Titolo, Testo, DataPubblicazione, Comune, Autore)
					  VALUES ('".$item->getTitle()."', '".$item->getContent()."', '".$item->getDate()."', $municipalityId, $idAutore)";
			//echo $query."<br>";
			mysqli_query($dbconn, $query);
			//echo mysqli_error($dbconn)."<br>";

			$articleCount++;
		}

		echo $articleCount;
	}

?>
