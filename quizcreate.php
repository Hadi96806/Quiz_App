<?php
    session_start();

    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    $quizid="";
    $x = $_SESSION['username'];
    $server = "localhost";
    $user = "root";    
    $pass = "";
    $db = "quizapp";
    $conn = new mysqli($server, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $x);
    $stmt->execute();
    $stmt->bind_result($userid);
    $stmt->fetch();
    $stmt->close();

    if (isset($_POST['createQuiz'])) {
        // Generate a unique ID for the quiz
        $quizid =  substr(uniqid(), -2);

        $_SESSION['quizid'] = $quizid;
        $_SESSION['title'] = $_POST['title'];

        $title = $_SESSION['title'];
        $total = 0;

        $sql = "INSERT INTO `quizzes` (`idquiz`, `id`, `title`, `total`) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $quizid, $userid, $title, $total);
        $stmt->execute();
        $stmt->close();

        // Close the database connection
        $conn->close();

        header("Location: addquestions.php?quizid=$quizid");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Your Quiz</title>
    <link rel="stylesheet" href="css/create.css">
    <script>
            function goBack() {
                window.history.back();
            }
        </script>
</head>
<body>
<center>
<main>
<header class="header">
	<a href="view.php?username=<?php echo $_SESSION['username']; ?>" class="logo"><h2>Quizziz</h2></a>
	<div class="header__buttons">
		<form method="post" action="">
            <input type="submit" class="header__button" name="logout" value="Logout">
        </form>
	</div>
</header>
    <div class="container">
        <form class="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <h2>Create your own quiz:</h2>
            <div class="form-control">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title"><br><br>
            </div>
            <div class="form-control">
                <input type="hidden" name="quizid" value="<?php echo $quizid; ?>">
            </div>
            <button type="submit" name="createQuiz">Create Quiz</button>
        </form>
    </div> 
</main>
</body>
</html>