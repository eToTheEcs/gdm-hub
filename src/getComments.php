<?php

	require_once 'dbUtils.php';

	if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["articleId"])) {

		$articleId = trim($_POST["articleId"]);

		$query = "SELECT C.TestoCommento, C.DataCommento, U.NomeUtente
				  FROM commenti C, utenti U
				  WHERE C.IdUtente = U.IdUtente AND
						C.IdArticolo = $articleId
				  ORDER BY C.DataCommento DESC";
		$qres = mysqli_query($dbconn, $query);
		if(mysqli_affected_rows($dbconn) != 0) {
			while ($row = mysqli_fetch_assoc($qres)) {
				echo "<div class='row comments mx-2'>
						<div class='col-md-9 col-sm-9 col-9 comment rounded mb-2'>
							<h4 class='m-0'><a href='#'>".$row["NomeUtente"]."</a></h4>
							<time class='text-white ml-3'>".$row["DataCommento"]."</time>
							<p class='mb-0 text-white'>".$row["TestoCommento"]."</p>
						</div>
					</div>";
			}
		}
		else {
			echo "<h1 class='heading'>no comments for this article.</h1>";
		}

	}
	else {
		echo "<h1>MISSING DATA</h1>";
	}

?>
