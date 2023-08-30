<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
      <title>Quizizz</title>
      <link rel="stylesheet" href="css/score.css">
    </head>
    <body>
    <header class="header">
        <a href="main.php" class="logo"><h2>Quizziz</h2></a>
        <div class="header__buttons">
            <a href="register.php" class="header__button">Create your own Quiz</a>
        </div>
    </header>
    <div class="container">
        <?php
            // Connect to the database
            $server = "localhost";
            $user = "root";
            $pass = "";
            $db = "quizapp";
            $conn = new mysqli($server, $user, $pass, $db);

            // Check for errors
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }   
            // Check if session variables are set
            if (isset($_SESSION['name'], $_SESSION['score'], $_SESSION['quizid'])) {
                $name = $_SESSION['name'];
                $score = $_SESSION['score'];
                $quizid = $_SESSION['quizid'];
                $total = $_SESSION['total'];
                
                if($score < $total/2){
                    echo"<h1>HardLuck! :(</h1>";
                } 
                else {
                    echo "<h1>Congratulations! :)</h1>";
                }
                echo"<br>";
                echo "<h2>You scored " . $score . " / " . $total . " on this quiz!</h2>";
            } 
            else {
                echo "Session variables not set.";
            }
        ?>
        <br><br>
		<a href="main.php" class="header__button">Create your own quiz</a>
    </div>
</body>
</html>

        
