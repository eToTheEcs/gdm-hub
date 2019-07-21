<?php

	class Article implements JsonSerializable {

		private $author;
		private $date;
		private $title;
		private $content;
		private $municipality;
		//private $imgUrl;
		private $id;

	public function __construct($id, $date, $title, $content, $author, $municipality/*, $imgUrl*/) {

			$this->date = $date;
			$this->title = $title;
			$this->content = $content;
			$this->author = $author;
			$this->municipality = $municipality;
			//$this->$imgUrl = $imgUrl;
			$this->id = $id;
		}

		/*public function getImgUrl() {
			return $this->$imgUrl;
		}*/

		public function getMunicipality() {
			return $this->municipality;
		}

		public function getDate() {
			return $this->date;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getContent() {
			return $this->content;
		}

		public function getAuthor() {
			return $this->author;
		}

		public function __toString() {

			return "<h3>pubblicato in data</h3><p> ".$this->date."</p><p><h1>".$this->title."</h1></p><p>".$this->content."</p>";
		}

		public function jsonSerialize() {

			$assocArr = [
				'id' => $this->id,
				'title' => $this->title,
				'municipality' => $this->municipality,
				'author' => $this->author,
				'content' => $this->content,
				'date' => $this->date
			];

			return $assocArr;
		}
	}

?>
