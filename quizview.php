<?php
    session_start();
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    if(isset($_POST['view'])){
        $quizid = $_POST["quizid"];
        $_SESSION['quizid'] = $quizid;
		header("Location: questionsview.php?quizid=$quizid");
		exit();
	}

    if(isset($_POST['results'])){
        $quizid = $_POST["quizid"];
        $_SESSION['quizid'] = $quizid;
		header("Location: viewresults.php?quizid=$quizid");
		exit();
	}

    if (isset($_POST["delete_quiz"])) {
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "quizapp";
    
        $conn = new mysqli($server, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $quizid = $_POST["quizid"];

        // Delete the quiz from the quizzes table
        $sql = "DELETE FROM quizzes WHERE idquiz = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $quizid);
        $stmt->execute();
        $stmt->close();
        
        // Delete all questions of the same quiz id from the questions table
        $sql = "DELETE FROM questions WHERE idquiz = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $quizid);
        $stmt->execute();
        $stmt->close();

        // Delete all results of quiz
        $sql = "DELETE FROM results WHERE idquiz = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $quizid);
        $stmt->execute();
        $stmt->close();

        $conn->close();
    }    
?>

<!DOCTYPE html>
<html>
<head>
    <title>Questions</title>
    <link rel="stylesheet" href="css/quizview.css">
</head>
<body>
    <header class="header">
        <a href="view.php?username=<?php echo $_SESSION['username']; ?>" class="logo"><h2>Quizziz</h2></a>
        <div class="header__buttons">
            <form method="post" action="">
                <input type="submit" class="header__button" name="logout" value="Logout">
            </form>
        </div>
    </header>
    <div class="container">
    <?php
        $x = $_GET['username'];
        // Connect to the database
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "quizapp";

        $conn = new mysqli($server, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $x);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();

        // Retrieve quiz titles from the database
        $sql = "SELECT idquiz, title FROM quizzes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display the quiz titles in a table with buttons to view and delete
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Title</th><th></th><th></th><th></th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["title"]."</td>
                <td><form method='post' action=''><input type='hidden' name='quizid' value='".$row["idquiz"]."'><button name='results' type='submit'>View Results</button></form></td>
                <td><form method='post' action=''><input type='hidden' name='quizid' value='".$row["idquiz"]."'><button name='view' type='submit'>View Quiz</button></form></td>
                <td><form method='post' action=''><input type='hidden' name='quizid' value='".$row["idquiz"]."'><button type='submit' name='delete_quiz'>Delete Quiz</button></form></td>
                </tr>";
            }
            echo "</table>";
        } 
        else {
            echo "No quizzes found.";
        }
        $conn->close();
    ?>
    
    </div>
</body>
</html>