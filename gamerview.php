<?php
	session_start();

	if(isset($_POST['start'])){
		$name = $_POST['name'];
		$_SESSION['name'] = $name;
		$quizid = $_SESSION['quizid'];
		header("Location: quizsolve.php?quizid=$quizid");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Quizziz</title>
	<link rel="stylesheet" href="css/gamerview.css">
</head>
<body>
<header class="header">
		<a href="main.php" class="logo"><h2>Quizziz</h2></a>
		<div class="header__buttons">
			<a href="register.php" class="header__button">Create your own Quiz</a>
		</div>
</header>
<div class="container">
    <form class="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<h1>Welcome</h1>
        <div class="form-control">
            <label for="title">Enter your name:</label>
            <input type="text" name="name" required><br><br>
			<input type="hidden" name="quizid" value="<?php echo $_SESSION['quizid']; ?>"
        </div>
        <button type='submit' name='start'>Start Quiz</button>
	</form>	
</div>
</body>
</html>