<?php
session_start();
define('ACCESS_TOKEN', 'BQC9whDMA5h26imjKhsspE2yjC3z250fOVH9lEOniPjLHnKBArN4ZYWXJ6WH6EVKerlV6aVMiHX8EAgXH7vxuGzZWq2HkB0ywmNJTqVvwAR_dfNwTrA7-XSNpRB77xsv9DzqzsF8j58FI9vzo_EZX8uMy3S6piHIHLJ0gVvZGe0EOkbH9A-m');
include('initial/config.php');
if(isset($_POST['submit'])){
	$search = $_POST['song'];
	$stmt = $con->prepare('INSERT INTO search (user_id,query) VALUES (?,?)');
	$stmt->bind_param('is',$_SESSION['userLoggedIn'],$search);
	$stmt->execute();
}
function getArtist($search){
	$curl = curl_init();
	$data = array(
		'q' => $search,
		'type' => 'track',
		'limit' => 1,
		'access_token' => ACCESS_TOKEN
	);
	$url = 'https://api.spotify.com/v1/search?'.http_build_query($data);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$artist = json_decode(curl_exec($curl));
	$src = $artist->tracks->items[0]->album->images[0]->url;
	echo '<script>
	document.body.style.background = `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("'.$src.'") no-repeat fixed`;
	document.body.style.backgroundSize = "100%";
	</script>';
	$artist = $artist->tracks->items[0]->artists[0]->name;
	
	if (curl_errno($curl)) {
		echo curl_error($curl);
	}
	curl_close($curl);
	return $artist;
}

function getLyrics($artist,$song){
	$curl = curl_init();

	$url = 'https://api.lyrics.ovh/v1/'.$artist.'/'.$song;
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$lyric = json_decode(curl_exec($curl));
	if (curl_errno($curl)) {
		echo curl_error($curl);
	}
	curl_close($curl);
	return $lyric;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
</head>
<style type="text/css">
html {
	height: 100%;
}
body {
	background: #000000;
	color: #ff4500;
	font-size: 1.3vw;
	background-position: center;
}

#main {
	background: rgba(0,0,0,0.6);
	border: 1px solid #ff4500;
	padding: 20px;
	width: 70%;
	margin: 0 auto;
	margin-top: 30px;
}
#lyrics {
	background: black;
	width: 50%;
	margin: 0 auto;
	margin-top: 20px;
	text-align: center;
	padding: 50px;
	letter-spacing: 2px;
	font-size: 1.4vw;
	border: 1px solid #ff4500;
}

#video {
   margin: 0 auto;
   text-align: center;
}
</style>
<body>
	<div id="main">
		<div id="video" class="container rounded">

		</div>
		<div id="lyrics" class="container rounded">
			<?php
			if(isset($_POST['submit'])){
				$artist = getArtist($_POST['song']);
				$lyrics = getLyrics(str_replace(' ','%20',$artist),str_replace(' ', '%20', $_POST['song']));
				$lyrics = str_replace('/\n/', '<br>', $lyrics->lyrics);
				echo '<div class="row">'.$lyrics.'</div>';
			}
			?>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script type="text/javascript">
		var search = "<?php echo $_POST['song']; ?>";
		var video = document.querySelector('#video');
		var lyrics = document.querySelector('#lyrics');
		console.log(search);
		$.ajax({
			type: 'GET',
			url: `https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=1&q=${search} song&type=video&key=AIzaSyAxLg6p1jxVFYd39vNtDcJ3-3228VBzQ88&videoCategoryId=10`,
			success: function(response){
				console.log(response);
				if(response.hasOwnProperty('items')){
					var videoId = response.items[0].id.videoId;
					var thumbnail = response.items[0].snippet.thumbnails.high.url;
					video.innerHTML = `<iframe src="http://www.youtube.com/embed/${videoId}" width="700" height="400"></iframe>`;
				}
			},
			error: function(){
				console.log('error');
			}
		})
	</script>
</body>
</html>