<?php

	header('Content-type: text/html;charset=utf-8');

	if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["comuni"])) {

		// decode the data
		$comuni = unserialize($_POST["comuni"]);

		/*foreach($comuni as $comune)
			echo $comune."; ";*/

		// collect the number of newly inserted articles, just for notification purposes

		$url = "http://localhost/gdm_hub/src/crawler.php";

		$totalArticleCount = 0;

		foreach($comuni as $comune) {

			$requestFields = array(
				"comune" => $comune
			);
			$postParams = http_build_query($requestFields);

			$curlConn = curl_init();
			curl_setopt($curlConn, CURLOPT_URL, $url);
			curl_setopt($curlConn, CURLOPT_POST, count($requestFields));
			curl_setopt($curlConn, CURLOPT_POSTFIELDS, $postParams);
			curl_setopt($curlConn, CURLOPT_RETURNTRANSFER, TRUE);

			$result = curl_exec($curlConn);

			//var_dump($result);

			//echo $comune.", ".$result."<br/>";
			$totalArticleCount += intval($result);
		}

		curl_close($curlConn);

		echo $totalArticleCount;

		/*ob_start();
		require "crawler.php";
		$crawlerOutput = ob_get_clean();*/

		//echo $crawlerOutput;
	}

?>
