<?php

	/*
	 * this file contains various utilities for the whole webapp
	 * author: Nicolas Benatti
	 */

	//namespace url {

	define("BASE_URL", "https://gazzettadimantova.gelocal.it/mantova/");

	function getSearchUrl($comune, $pageIndex) {

		return BASE_URL . "ricerca?location=" . trim($comune) . "&page=" . $pageIndex;
	}

	function splitDateTime($dateTime, $delimiter) {

		$tokens = explode($delimiter, $dateTime);
		$date = Date("d-m-Y", strtotime($tokens[0]));
		$time = Date("H:i:s", strtotime($tokens[1]));

		return array($date, $time);
	}
	//}
?>
