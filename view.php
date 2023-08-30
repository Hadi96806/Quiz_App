<?php
if(isset($_GET['username'])){
    $x = $_GET['username'];
	if(isset($_POST['create'])){
		header("Location: quizcreate.php?username=$x");
		exit();
	}

	if(isset($_POST['show'])){
		header("Location: quizview.php?username=$x");
		exit();
	}
}
// Logout button handler
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Quizziz</title>
	<link rel="stylesheet" href="css/view.css">
</head>
<body>
<header class="header">
	<a href="#" class="logo"><h2>Quizziz</h2></a>
	<div class="header__buttons">
		<form method="post" action="">
            <input type="submit" class="header__button" name="logout" value="Logout">
        </form>
	</div>
</header>
<div class="container">
	<form method="post" action="" class="form">
		<h1>Welcome Back</h1>
		<button type='submit' name='create' class='but'>Create Quiz</button>
		<br><br>
		<button type='submit' name='show' class='but'>View Quizes</button>
	</form>	
</div>
</body>
</html>