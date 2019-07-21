<?php

	/**
	 * use this script when municipality data gets lost for some reason
	 */

	require_once '..\\libs\\simplehtmldom\\simple_html_dom.php';
	require_once 'utils.php';
	require_once "dbUtils.php";

	$page = file_get_html("https://it.wikipedia.org/wiki/Provincia_di_Mantova");

	$comuniLink = $page->find(".colonne_strette ul li a");
	//$nomiComuni = array();

	foreach($comuniLink as $comune) {

		/*$encodedNomeComune = str_replace(" ", "_", $comune->innertext);
		$paginaComune = file_get_html("https://it.wikipedia.org/wiki/".$encodedNomeComune);
		$latitudine = substr($paginaComune->find(".geo-dms .latitude"));
		$longitudine = $paginaComune->find(".geo-dms .longitude");*/

		$query = "INSERT INTO comuni (Nome)
				  VALUES ('".addslashes($comune->innertext)."')";
		mysqli_query($dbconn, $query);

		echo mysqli_error($dbconn);
	}

?>
