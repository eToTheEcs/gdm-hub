<?php
	
	class CookieManager {

		private $dates;

		public function __construct() {

			$this->dates = array();
		}

		public function addDate($date) {
			array_push($this->dates, $date);
		}

		public function removeLastDate() {

			array_pop($this->dates);
		}

		// svuota il buffer di date
		public function flush() {

			$this->dates = array();
		}			

		public function toString() {

			return json_encode($this->dates);
		}
	}

	session_start();

	if(!isset($_SESSION["managerObj"]))
		$_SESSION["managerObj"] = new CookieManager();
?>