<?php
session_start();
include('initial/config.php');
 if(isset($_SESSION['username'])){
   $stmt = $con->prepare('SELECT * FROM user WHERE username=?');
   $stmt->bind_param('s',$_SESSION['username']);
   $stmt->execute();
   $res = $stmt->get_result();
   $res = $res->fetch_assoc();
   $_SESSION['userLoggedIn'] = $res['id'];
 }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<style type="text/css">
    body {
    	background: #000000;
    	color: white;
    }
    .container {
    	width: 60%;
        border: 1px solid #ff4500;
        margin-top: 10px;
    }
	.form {
		margin: 0 auto;
		margin-top: 50px;
		padding: 10px;
	}
	input {
		margin-bottom: 10px;
	}
</style>
<body>
	<div class="container form rounded">
	<form action="result.php" method="POST">
		<input type="text" class="form-control" name="song" id="song" placeholder="Song">
		<input type="submit" name="submit" class="btn btn-primary" value="search">		
	</form>
	</div>
	<div class="container">
		<h2>History</h2>
		<ul class="list">
			<?php
               $stmt = $con->prepare('SELECT * FROM search WHERE user_id=?');
               $stmt->bind_param('i',$_SESSION['userLoggedIn']);
               $stmt->execute();
               $res = $stmt->get_result();
               while($row = $res->fetch_assoc()){
               	echo '<li>'.$row['query'].'</li>';
               }
			?>
		</ul>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</body>
</html>