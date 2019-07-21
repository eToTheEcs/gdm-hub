<?php

	if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["sendLogoutAction"])) {

		// aggiorna la data di ultimo accesso
		/*if(!isset($_COOKIE["lastAccess"])) {
			setcookie("lastAccess", Date("d-m-Y H:i:s"), time()+3600, "/");
		}
		else {
			$_COOKIE["lastAccess"] = Date("d-m-Y H:i:s");

			echo "<h1> LAST ACCESS ".$_COOKIE["lastAccess"]."</h1>";
		}*/

		// aggiorna la data di ultimo accesso
		setcookie("lastAccess", Date("d-m-Y H:i:s"), time()+(3600*24*30), "/");

		session_start();
		session_unset();
		if(!session_destroy())
			echo "<h1> Error in destroying the session</h1>";
		header("Location: login.php");
	}

?>
