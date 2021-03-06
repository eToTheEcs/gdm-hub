<?php
	require_once "CookieManager.php";
	require_once "utils.php";
	require_once "dbUtils.php";

	// check if user is logged in
	if(empty($_SESSION["userName"]) || empty($_SESSION["password"])) {

		header("Location: login.php");
	}

	// update the last access timestamp
	/*if(!isset($_COOKIE["lastAccess"])) {
		setcookie("lastAccess", Date("d-m-Y H:i:s"), time()+3600, "/");
	}
	else {
		$_COOKIE["lastAccess"] = Date("d-m-Y H:i:s");
	}*/

	//setcookie("lastAccess", Date("d-m-Y H:i:s"), -100, "/");

	// get
?>
<!doctype HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gazzetta di Mantova Hub</title>
		<link rel="shortcut icon" href="..\res\images\favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link rel="stylesheet" href="..\res\styles\home_style.css"/>
		<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Raleway:400,400i,500,700,700i" rel="stylesheet">
		<!-- LIBS -->
		<script
			  src="https://code.jquery.com/jquery-3.4.0.min.js"
			  integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
			  crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	</head>
    <body lang="it">

		<!-- navbar -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="home.php">
				<!-- LOGO -->
				<svg viewBox="0 0 1000 26">
					<g fill="#ffffff" fill-rule="evenodd">
						<path d="M16.518 18.779h.41c.268-.02.367-.053.441-.293l-.322-2.553c-.02-.265-.178-.368-.396-.401h-1.942c-.211.02-.335.145-.401.347l-.372 2.367c-.041.268 0 .526.314.533h.566c.231 0 .426.194.426.426v6.114h-5.106v-6.114c0-.232.19-.426.425-.426h.289a.615.615 0 0 0 .562-.454L14.044.881h4.573c.76 5.814 1.525 11.631 2.285 17.444.05.227.285.442.607.454h.244c.235 0 .425.194.425.426v6.114h-6.085v-6.114c0-.232.19-.426.425-.426zm.174-6.13v.016a.245.245 0 0 1-.236.24h-1.462a.24.24 0 0 1-.24-.24v-.016l.938-6.24a.144.144 0 0 1 .145-.127c.074 0 .132.05.144.115V6.4c.004.005.004.013.004.017l.707 6.23zM84.823 18.779h.41c.268-.02.368-.053.442-.293-.107-.852-.215-1.702-.319-2.553-.024-.265-.181-.368-.4-.401h-1.942c-.21.02-.334.145-.397.347l-.371 2.367c-.045.268-.004.526.31.533h.566c.236 0 .425.194.425.426v6.114H78.44v-6.114c0-.232.194-.426.426-.426h.288a.614.614 0 0 0 .562-.454L82.35.881h4.574c.763 5.814 1.524 11.631 2.288 17.444.046.227.281.442.603.454h.249a.43.43 0 0 1 .425.426v6.114h-6.09v-6.114c0-.232.19-.426.425-.426zm.174-6.13v.016a.242.242 0 0 1-.235.24H83.3a.24.24 0 0 1-.24-.24v-.016l.938-6.24h.005a.143.143 0 0 1 .145-.127c.07 0 .127.05.14.115l.003.004v.017l.707 6.23zM147.4 18.779h.408c.269-.02.368-.053.442-.293l-.321-2.553c-.022-.265-.178-.368-.397-.401h-1.942c-.21.02-.335.145-.397.347-.123.789-.248 1.578-.376 2.367-.042.268 0 .526.314.533h.566c.231 0 .426.194.426.426v6.114h-5.106v-6.114c0-.232.189-.426.425-.426h.289a.616.616 0 0 0 .562-.454L144.925.881h4.573c.764 5.814 1.525 11.631 2.289 17.444.046.227.281.442.603.454h.248a.43.43 0 0 1 .425.426v6.114h-6.09v-6.114c0-.232.191-.426.427-.426zm.173-6.13v.016a.243.243 0 0 1-.236.24h-1.462a.24.24 0 0 1-.24-.24v-.016l.938-6.24h.004a.144.144 0 0 1 .145-.127c.07 0 .128.05.14.115h.005v.021l.706 6.23zM208.617 18.818h.41c.267-.02.366-.053.437-.293-.103-.851-.211-1.701-.318-2.553-.02-.264-.178-.368-.397-.4h-1.941c-.211.02-.335.144-.401.346l-.372 2.367c-.042.269 0 .526.313.533h.566c.232 0 .427.195.427.426v6.115h-5.107v-6.115c0-.23.19-.426.425-.426h.29c.272-.02.504-.206.562-.453L206.142.92h4.573c.765 5.814 1.526 11.63 2.285 17.444.05.227.285.442.608.453h.243c.236 0 .426.195.426.426v6.115h-6.086v-6.115c0-.23.19-.426.426-.426zm.174-6.13v.016a.246.246 0 0 1-.236.24h-1.464a.24.24 0 0 1-.238-.24v-.016l.938-6.239a.143.143 0 0 1 .144-.127c.075 0 .132.05.145.115v.004c.004.004.004.013.004.017l.707 6.23zM8.775 10.494H6.407a.345.345 0 0 1-.342-.347V8.66c.012-2.438-2.355-2.029-2.396.054-.211 2.693-.166 5.386-.054 8.08-.132 2.206 2.239 2.583 2.289.347.017-.462.037-.921.054-1.383 0-.31-.182-.496-.641-.505H5.16a.349.349 0 0 1-.347-.347v-2.872c0-.189.157-.342.347-.342h4.999c.19 0 .343.153.343.342v2.872c0 .19-.153.347-.343.347h-.562c-.318.03-.442.236-.475.533v9.519l-1.409.025c-.198-.45-.392-.905-.586-1.356-.15-.193-.302-.235-.45.054-1.104 2.137-4.041 2.31-5.5.554C.182 23.384.037 21.678.03 18.285.02 15.352.009 12.42 0 9.482.066 5.359.215 1.376 2.347.587c.772-.285 2.42-.479 3.185.55.264.31.463.462.533.048v-.024c0-.19.153-.347.342-.347h2.368c.19 0 .347.157.347.347v8.986c0 .19-.157.347-.347.347M23.973 10.073h2.375a.242.242 0 0 0 .243-.239V7.938v.007c.005-.43.186-.64.542-.664h1.801c.236-.008.356.252.206.562-2.011 3.582-4.023 7.163-6.04 10.75-.06.26-.123.516-.185.776v5.962h8.494v-.005h1.47v-9.448h-3.094v2.227c-.004.004-.008.004-.008.004 0 .455-.063.876-.603.876h-1.731c-.434 0-.592-.22-.351-.624l5.726-10.597c.07-.157.11-.145.11-.392V1.12a.24.24 0 0 0-.242-.239h-8.713a.243.243 0 0 0-.244.24v8.713c0 .132.112.239.244.239M34.99 10.073h2.376a.243.243 0 0 0 .244-.239V7.938v.007c.004-.43.186-.64.541-.664h1.802c.234-.008.355.252.21.562-2.016 3.582-4.029 7.163-6.044 10.75l-.187.776v5.962h8.499c0-.005-.004-.005-.004-.005h1.47v-9.448h-3.094v2.227c-.004.004-.008.004-.008.004 0 .455-.062.876-.603.876h-1.73c-.435 0-.592-.22-.352-.624l5.726-10.597c.07-.157.111-.145.111-.392V1.12a.24.24 0 0 0-.24-.239H34.99a.239.239 0 0 0-.239.24v8.713c0 .132.107.239.239.239M44.59.881h10.09v9.742h-2.773a.257.257 0 0 1-.26-.256V7.743v.005a.542.542 0 0 0-.47-.554h-1.435a.54.54 0 0 0-.47.554v3.18c0 .343.273.554.512.55 0 .004-.005.004-.005.004h2.488c.124 0 .223.1.223.223v2.17c0 .119-.1.218-.223.218h-2.503a.543.543 0 0 0-.492.554v3.627c0 .34.265.55.503.554h1.471a.54.54 0 0 0 .505-.529v-2.71h2.928v9.75H44.51v-5.958c0-.305.247-.553.553-.553h.694V7.545c0-.314-.364-.437-.806-.45h-.049a.342.342 0 0 1-.31-.339V.881M59.28 7.194a.536.536 0 0 1 .516.554c.004-.01.004-.017.008-.02v10.571c-.012.326-.281.533-.516.53h.003-.288a.522.522 0 0 0-.52.52v5.99h6.101v-5.99a.523.523 0 0 0-.52-.52h-.285.004c-.244.003-.517-.212-.517-.555v.004V7.652a.526.526 0 0 1 .509-.458c-.004 0-.004 0-.004-.004h.672v.004c.24-.004.513.206.513.554.004-.005.004-.01.004-.013v3.701c0 .1.083.182.182.182h1.764a.18.18 0 0 0 .178-.182V.881H55.966v10.555c0 .1.083.182.182.182h1.764a.18.18 0 0 0 .178-.182V7.735c.004.004.004.008.004.013 0-.348.276-.558.516-.554l-.004-.004h.678c0 .004-.004.004-.004.004M71.178 7.194c.239-.004.516.206.516.554.004-.01.004-.017.008-.02v10.571c-.012.326-.28.533-.517.53h.004-.288a.522.522 0 0 0-.52.52v5.99h6.1v-5.99a.522.522 0 0 0-.52-.52h-.285.004c-.239.003-.516-.212-.516-.555v.004V7.652a.526.526 0 0 1 .509-.458c-.005 0-.005 0-.005-.004h.673v.004c.24-.004.513.206.513.554.005-.005.005-.01.005-.013v3.701c0 .1.082.182.181.182h1.765c.098 0 .18-.082.18-.182V.881h-11.12v10.555c0 .1.082.182.181.182h1.764a.18.18 0 0 0 .178-.182V7.735c.004.004.004.008.004.013 0-.348.276-.558.516-.554l-.004-.004h.677c0 .004-.003.004-.003.004M172.093 7.194c.239-.004.512.206.512.554.004-.01.008-.017.008-.02v10.571c-.012.326-.281.533-.513.53h-.288a.519.519 0 0 0-.517.52v5.99h6.098v-5.99c0-.286-.231-.52-.516-.52h-.286c-.24.003-.516-.212-.516-.555v.004V7.652a.53.53 0 0 1 .509-.458s-.005 0-.005-.004h.677c0 .004-.004.004-.004.004a.536.536 0 0 1 .517.554c0-.005 0-.01.004-.013v3.701c0 .1.079.182.178.182h1.764c.099 0 .18-.082.18-.182V.881H168.78v10.555c0 .1.077.182.177.182h1.764a.184.184 0 0 0 .182-.182V7.735c0 .004 0 .008.003.013 0-.348.273-.558.513-.554V7.19h.674v.004M114.896 7.083c-.227.016-.47.227-.47.55l-.005-.005v10.617c.033.306.29.492.512.488 0 .004-.003.004-.003.004h.202c.277 0 .505.228.505.5v6.123h-5.908v-6.123c0-.272.223-.5.5-.5h.193c.24.004.516-.21.516-.553v.005V7.632a.536.536 0 0 0-.474-.549h-.236a.503.503 0 0 1-.5-.504V.88h5.909V6.58a.506.506 0 0 1-.505.504h-.236M97.83.881h7.081c3.756 0 3.71 6.197 3.649 12.018.029 5.912.215 12.44-3.49 12.44h-6.901a.342.342 0 0 1-.338-.343V19.15a.34.34 0 0 1 .338-.339h.38s-.004 0-.004-.004c.24.004.512-.206.512-.549.004 0 .004 0 .004.004V7.706v-.074s0 .004-.004.004c0-.343-.273-.558-.512-.554 0 0 .005 0 .005-.004h-.397a.322.322 0 0 1-.322-.322V.881zm4.983 6.197c.301-.004.611.017.938 0 1.31.14 1.153 3.045 1.214 5.847.004 4.66-.14 5.886-1.148 5.886h-.959c-.14 0-.463-.11-.459-.545.004-3.566 0-7.13 0-10.696 0-.384.253-.492.414-.492zM124.137 7.095c.24 0 .513.21.513.553.004-.003.004-.008.004-.012v10.622c0-.004 0-.008-.004-.013 0 .31-.223.516-.446.549h-.53a.286.286 0 0 0-.284.286v6.259h5.193V19.08a.287.287 0 0 0-.285-.286h-.71a.538.538 0 0 1-.443-.532V6.776c.02-.223.327-.269.368 0 1.02 6.186 2.045 12.374 3.065 18.563h.988l2.8-18.587c.022-.236.323-.236.37-.004V18.24v-.004a.54.54 0 0 1-.518.554c.004 0 .004 0 .009.003h-.212a.287.287 0 0 0-.285.286v6.259h5.545V19.08a.287.287 0 0 0-.285-.286h-.343c.004-.003.008-.003.008-.003-.24.003-.513-.211-.513-.554-.004 0-.004.004-.004.004V7.574a.541.541 0 0 1 .513-.48h-.004.343a.286.286 0 0 0 .285-.285V.9h-.521V.88h-5.623l-1.412 9.961c-.05.306-.401.269-.438 0l-1.5-9.96h-3.322l-.003.016h-3.066V6.81c0 .158.128.286.285.286h.462M185.882 0h.107c2.623 0 4.759 1.908 4.759 6.75v11.41c0 6.244-2.14 7.529-4.76 7.529h-.106c-2.62 0-4.763-.802-4.763-7.528V6.697c0-4.93 2.143-6.697 4.763-6.697zm.041 6.808c.735 0 1.338 1.082 1.338 2.347v7.296c0 1.458-.603 2.325-1.338 2.325-.74 0-1.343-.867-1.343-2.325V9.155c0-1.281.603-2.347 1.343-2.347zM154.756.885h2.466V.881h3.02c1.092 5.169 2.182 10.336 3.272 15.505.112.392.591.277.596-.128l.004.004V7.078h-1.149a.244.244 0 0 1-.244-.244V.881h5.02v5.953a.243.243 0 0 1-.244.244h-1.153v18.261h-3.61c-1.348-6.086-2.698-12.176-4.049-18.261-.082-.322-.496-.408-.529 0V18.65h1.297c.132 0 .244.111.244.244v6.428h-5.019v-6.428c0-.133.11-.244.243-.244h1.004V7.145H155a.244.244 0 0 1-.243-.245V.885M196.755 7.29a.539.539 0 0 0-.516.544l1.61 10.721c.042.24.332.248.36 0 .381-3.574.757-7.147 1.137-10.721-.004.004-.004.004-.004.009a.541.541 0 0 0-.517-.554h.004-.264a.465.465 0 0 1-.462-.463V.881h4.783v5.945a.462.462 0 0 1-.462.463h-.182.005a.53.53 0 0 0-.497.401l-2.169 17.612-3.85-.004-2.801-17.471c-.005.003-.005.011-.009.016a.538.538 0 0 0-.511-.554h.004-.256a.462.462 0 0 1-.463-.463V.881h5.441v5.945c0 .232-.17.426-.397.46.007 0 .012.003.016.003"></path>
					</g>
				</svg>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbar">
				<ul class="navbar-nav mr-auto">
					<!--<li class="nav-item active">
						<a class="nav-link" href="#">Home <span class="sr-only" style="color: black;">(current)</span></a>
					</li>-->
					<li class="nav-item-active">
						<a href="feed.php" class="nav-link">Feed</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="accountShow.php">Account</a>
					</li>
					<!--<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
						<div class="dropdown-menu" aria-labelledby="dropdown05">
							<p><a class="dropdown-item" href="home.php" class="text-dark">What's new</a></p>
						</div>
					</li>-->
				</ul>

				<!-- logout dropdown -->
				<form class="form-inline my-2 my-md-0" method="post" action="logout.php">
					<!--<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="#">Your profile</a>
					</div>-->
					<input class="btn btn-primary" type="submit" name="sendLogoutAction" value="Logout"/>
				</form>
			</div>
  		</nav>
		<!-- /navbar -->

		<div class="container">
			<!-- page body -->
			<!-- title -->
			<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
				<p class="h1 heading">News feed</p>
			</div>
			<!-- /title -->
			<!-- search bars -->
			<div class="px-3 py-5 pt-md-5 pb-md-4 mx-5 text-center">
				<div class="form-group">
					<form id="searchForm">
						<div class="form-group row">
							<label for="q" class="col-sm-2 col-form-label">Totolo & Testo</label>
							<div class="col-sm-10">
								<input type="text" id="q"
								name="q"
								autocomplete="off"
								class="form-control px-2"
								placeholder="nuovo sindaco, mille miglia, incidente, elezioni, ..."
								aria-label="Search"
								value="<?php
											if(isset($_GET["q"])) {
												echo $_GET["q"];
											}
											elseif (isset($_SESSION["lastq"]) || $_SESSION["lastq"] != "") {
												echo urldecode($_SESSION["lastq"]);
											}
											else {
												echo "";
											}
										?>" />
							</div>
						</div>
						<div class="form-group row">
							<label for="fromDate" class="col-sm-2 col-form-label">Da</label>
							<div class="col-sm-10">
								<input type="text"
								id="fromDate"
								name="fromDate"
								autocomplete="off"
								class="form-control px-2"
								placeholder="da"
								onfocus="(this.type='date')"
								onfocusout="(this.type='text')"
								aria-label="Search"/>
							</div>
						</div>
						<div class="form-group row">
							<label for="toDate" class="col-sm-2 col-form-label">A</label>
							<div class="col-sm-10">
								<input type="text"
								id="toDate"
								name="toDate"
								autocomplete="off"
								class="form-control px-2"
								placeholder="a"
								onfocus="(this.type='date')"
								onfocusout="(this.type='text')"
								aria-label="Search"/>
							</div>
						</div>
						<div class="form-group row">
							<label for="toDate" class="col-sm-2 col-form-label">Comuni</label>
							<div class="col-sm-10">
								<?php
									$query = "SELECT C.IdComune, C.Nome
											  FROM comuni C, preferisce P, utenti U
											  WHERE C.IdComune = P.Comune AND U.IdUtente = P.Utente
											        AND P.Utente = ".$_SESSION["IdUtente"];
									$qres = mysqli_query($dbconn, $query);
									//echo "<select multiple class='form-control my-3 fieldData' name='comuni[]' id='comuni'>";
									echo "<div class='class='form-control my-3 fieldData scrollable col-sm-10'>";
									while($row = mysqli_fetch_assoc($qres)) {
										//echo "<input type='checkbox' value='".$row["IdComune"]."'>".$row["Nome"]."</option>";
										echo "<div class='custom-control custom-checkbox text-left'>
											      <input type='checkbox' class='custom-control-input' id='comune_".$row["IdComune"]."' value='".$row["IdComune"]."' name='comuni[]'>
												  <label class='custom-control-label' for='comune_".$row["IdComune"]."'>".$row["Nome"]."</label>
											  </div>";
									}
									echo "</div>";
									//echo "</select>";
								?>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- /search bars -->

			<!-- result list -->
			<div class="list-group">
				<?php

					// finds the author of the article from his/her ID
					function resolveAuthor($dbconn, $authorId) {

						$query = "SELECT NomeAutore
								  FROM autori
								  WHERE IdAutore = '$authorId'";
						$qres = mysqli_query($dbconn, $query);

						return trim(mysqli_fetch_assoc($qres)["NomeAutore"]);
					}

					// finds the municipality of the article from his/her ID
					function resolveMunicipality($dbconn, $munId) {

						$query = "SELECT Nome
								  FROM comuni
								  WHERE IdComune = '$munId'";
					  	$qres = mysqli_query($dbconn, $query);

						return trim(mysqli_fetch_assoc($qres)["Nome"]);
					}

					function formatResultText($text, $occurrenceIndex, $needleLen) {

						if($occurrenceIndex == -1) {
							return $text;
						}


						$transformedText = substr_replace($text, "<span class='spicyText'>".substr($text, $occurrenceIndex, $needleLen)."</span>", $occurrenceIndex, $needleLen);

						return "<span>$transformedText</span>";
					}

					function arrayAsList($v) {

						$s = "";

						for($i = 0; $i < count($v); $i++) {

							$s .= $v[$i];

							if($i < count($v)-1)
								$s .= ", ";
						}

						return $s;
					}

					$searchQuery = "";	// the query
					$q = "";			// the keyword

					// get data and build the query
					if($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["q"])) {

						$q = filter_var($_GET["q"], FILTER_SANITIZE_ENCODED);
						$_SESSION["lastq"] = $q;

						// build the query
						if(!empty($_GET["fromDate"])) {
							$fromDate = $_GET["fromDate"];
						}
						else {
							$fromDate = "";
						}

						if(!empty($_GET["toDate"])) {
							$toDate = $_GET["toDate"];
						}
						else {
							$toDate = "";
						}

						if(!empty($_GET["comuni"])) {
							$comuni = array();
							foreach($_GET["comuni"] as $comuneId) {
								array_push($comuni, $comuneId);
							}

							$subquery = arrayAsList($comuni);
						}
						else {
							$comuni = NULL;
							$subquery = "SELECT Comune
										  FROM preferisce
										  WHERE Utente = ".$_SESSION['IdUtente'];
						}

						//var_dump($comuni);

						$searchQuery = "SELECT *, (INSTR(Titolo, '".urldecode($q)."')-1) AS KeywordLocationTitle, (INSTR(Testo, '".urldecode($q)."')-1) AS KeywordLocationText
							  	  		FROM articoli
										WHERE Comune IN ($subquery)
											AND Titolo LIKE '%".urldecode($q)."%'
											OR Testo LIKE '%".urldecode($q)."%'".
											(($fromDate != "") ? "AND DataPubblicazione >= '$fromDate 00:00:01' " : "").
											(($toDate != "") ? "AND DataPubblicazione <= '$toDate 00:00:01'" : "")."
										ORDER BY DataPubblicazione DESC";


						$qres = mysqli_query($dbconn, $searchQuery);
						echo mysqli_error($dbconn);

						//echo $searchQuery;

						/*while ($row = mysqli_fetch_assoc($qres)) {
							//echo $row["Titolo"].", ".$row["KeywordLocation"]."<br>";

							echo "<a href='articleShow.php?articleid=".$row["IdArticolo"]."' class='list-group-item list-group-item-action flex-column align-items-start'>
									<div class='d-flex w-100 justify-content-between'>
										<h5 class'mb-1'>".formatResultText($row["Titolo"], $row["KeywordLocation"], strlen($q))."</h5>
										<small>".$row["DataPubblicazione"]."</small>
										<!--<small>".resolveMunicipality($dbconn, $row["Comune"])."</small>-->
									</div>
									<p class='mb-1'> ".substr($row["Testo"], 0, 50)." [...] </p>
									<small class='spicyText'>Di ".resolveAuthor($dbconn, $row["Autore"])."</small>
									<div class='w-100'></div>
									<small class='spicyText'>".resolveMunicipality($dbconn, $row["Comune"])."</small>
								</a>";
						}*/
					}
					else {

						$_SESSION["lastq"] = "";

						if(!empty($_GET["fromDate"])) {
							$fromDate = $_GET["fromDate"];
						}
						else {
							$fromDate = "";
						}

						if(!empty($_GET["toDate"])) {
							$toDate = $_GET["toDate"];
						}
						else {
							$toDate = "";
						}

						if(!empty($_GET["comuni"])) {
							$comuni = array();
							foreach($_GET["comuni"] as $comuneId) {
								array_push($comuni, $comuneId);
							}

							$subquery = arrayAsList($comuni);
						}
						else {
							$comuni = NULL;
							$subquery = "SELECT Comune
										  FROM preferisce
										  WHERE Utente = ".$_SESSION['IdUtente'];
						}

						$searchQuery = "SELECT *
										FROM articoli
										WHERE Comune IN ($subquery)".
												(($fromDate != "") ? "AND DataPubblicazione >= '$fromDate 00:00:01' " : "").
					 							(($toDate != "") ? "AND DataPubblicazione <= '$toDate 00:00:01'" : "")."
										ORDER BY DataPubblicazione DESC";

						$qres = mysqli_query($dbconn, $searchQuery);
						echo mysqli_error($dbconn);
					}

					//echo $searchQuery;

					// print results
					while ($row = mysqli_fetch_assoc($qres)) {

						if(!isset($row["KeywordLocationTitle"]))
							$row["KeywordLocationTitle"] = -1;

						echo "<a href='articleShow.php?articleid=".$row["IdArticolo"]."' class='list-group-item list-group-item-action flex-column align-items-start'>
								<div class='d-flex w-100 justify-content-between'>
									<h5 class'mb-1'>".formatResultText($row["Titolo"], $row["KeywordLocationTitle"], strlen($q))."</h5>
									<small>".$row["DataPubblicazione"]."</small>
									<!--<small>".resolveMunicipality($dbconn, $row["Comune"])."</small>-->
								</div>
								<p class='mb-1'> ".substr($row["Testo"], 0, 50)." [...] </p>
								<small class='spicyText'>Di ".resolveAuthor($dbconn, $row["Autore"])."</small>
								<div class='w-100'></div>
								<small class='spicyText'>".resolveMunicipality($dbconn, $row["Comune"])."</small>
							</a>";
					}
				?>
			</div>
			<!-- /result list -->
			<!-- footer -->
			<footer class="pt-4 my-md-5 pt-md-5 border-top">
				<div class="row">
					<div class="col-12 col-md">
						<img class="mb-2" src="..\res\images\Provincia_di_Mantova-Stemma.png" alt="" width="40" height="50">
						<small class="d-block mb-3 text-muted">© 2019-2021</small>
					</div>
					<!--<div class="col-6 col-md">webmaster: Nicolas Benatti</div>-->
					<div class="col-6 col-md">
						<h5>Contatti</h5>
						<ul class="list-unstyled text-small">
						<li><a class="text-muted" href="mailto:nicolas.benatti00@gmail.com">Mandami una mail</a></li>
						</ul>
					</div>
					<div class="col-6 col-md">
						<h5>Webmaster</h5>
						<ul class="list-unstyled text-small">
						<li><a class="text-muted" target="_blank" href="https://www.linkedin.com/in/nicolas-benatti">Nicolas Benatti</a></li>
						</ul>
					</div>
				</div>
			</footer>
			<!-- /footer -->
		</div>
		<!-- /page body -->
    </body>

	<script type="text/javascript">

		// detects if the user presser the enter key and therefore submits the form
		// this is the equivalent of adding an <onkeypress> event in "plain" JavaScript
		/*$("#searchForm").keypress(function(e) {

			if(e.which == 12) {
				console.log("YOU PRESSED ENTER");
			}
		});*/

		// naturally submit the form when pressing enter
		// even if not all the fields contain data
		document.getElementById('searchForm').addEventListener('keypress', function(event) {

			if (event.keyCode == 13) {

				//console.log(this);
				this.submit();
	        }
    	});

	</script>
</html>
