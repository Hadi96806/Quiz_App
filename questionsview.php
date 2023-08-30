<?php
    session_start();
    $quizid = $_GET["quizid"];
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }

    if (isset($_POST["update_question"])) {
        // Connect to the database
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "quizapp";

        $conn = new mysqli($server, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $quesid = $_POST["quesid"];
        $question = $_POST["question"];
        $choice_1 = $_POST["choice_1"];
        $choice_2 = $_POST["choice_2"];
        $choice_3 = $_POST["choice_3"];
        $choice_4 = $_POST["choice_4"];
        $answer = $_POST["answer"];

        $sql = "UPDATE questions SET question = ?, choice_1 = ?, choice_2 = ?, choice_3 = ?, choice_4 = ?, answer = ? WHERE idques = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $question, $choice_1, $choice_2, $choice_3, $choice_4, $answer, $quesid);
        $stmt->execute();

        $conn->close();
    }

    if (isset($_POST["delete_question"])) {
        // Connect to the database
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "quizapp";

        $conn = new mysqli($server, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $quizid = $_GET['quizid'];
        // decrease nb of questions by 1
        $stmt = $conn->prepare("SELECT `total` from `quizzes` WHERE idquiz = ?");
        $stmt->bind_param("s", $quizid);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $row = $resultSet->fetch_assoc();
        $total = (int)$row['total'];
        $stmt->close();
        $total = $total - 1;
        $stmt = $conn->prepare("UPDATE quizzes SET total = ? WHERE idquiz = ?");
        $stmt->bind_param("is", $total, $quizid);
        $stmt->execute();
        $stmt->close();

        $quesid = $_POST["quesid"];
        $sql = "DELETE FROM questions WHERE idques = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $quesid);
        $stmt->execute();

        $conn->close();
    }

    if (isset($_POST["add_question"])) {
        header("Location: addquestions.php?quizid=$quizid");
        exit();
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
        if (!isset($_GET['quizid']) || empty($_GET['quizid'])) {
            echo "Quiz ID is missing.";
            exit();
        }
        $quizid = $_GET['quizid'];
        // Connect to the database
        $server = "localhost";
        $user = "root";
        $pass = "";
        $db = "quizapp";

        $conn = new mysqli($server, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT idques, question, choice_1, choice_2, choice_3, choice_4, answer FROM questions WHERE idquiz = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $quizid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display the quiz questions in a table with button to delete
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Question</th><th>Choice 1</th><th>Choice 2</th><th>Choice 3</th><th>Choice 4</th><th>Answer</th><th></th><th></th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                <form method='post' action=''>
                <td><input type='text' name='question' value='".$row["question"]."'></td>
                <td><input type='text' name='choice_1' value='".$row["choice_1"]."'></td>
                <td><input type='text' name='choice_2' value='".$row["choice_2"]."'></td>
                <td><input type='text' name='choice_3' value='".$row["choice_3"]."'></td>
                <td><input type='text' name='choice_4' value='".$row["choice_4"]."'></td>
                <td><input type='text' name='answer' value='".$row["answer"]."'></td>
                <td><input type='hidden' name='quesid' value='".$row["idques"]."'><button type='submit' name='delete_question'>Delete</button></td>
                <td><input type='hidden' name='quesid' value='".$row["idques"]."'><button type='submit' name='update_question'>Update</button></td></form></tr>";
            }
            echo"</table>";
        }
        
        else {
            echo "No questions found.";
        }
        echo"<br><br>";
        echo"<form method='post' action=''>";
            echo"<input type='hidden' name='quizid' value='" . $quizid. "'><button class='header__button' type='submit' name='add_question'>Add Question</button>";
        echo"</form>";
        $conn->close();
    ?>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<title>Quiz Link Generator</title>
</head>
<body>
<div class="container">
    <h1>Quiz Link Generator</h1>
    <form id="quizForm">
        <div>
            <button class="header__button" type="button" onclick="generateQuizLink()">Generate Quiz Link</button>
            <button class="header__button" type="button" onclick="copyQuizLink()">Copy Link</button>
            <br><br>
            <textarea id="quizLink" readonly></textarea>
        </div>
    </form>
</div>

<script>
    function generateQuizLink() {
        var quizLink = window.location.origin + '/Quiz/gamerview.php?quizid=<?php echo $quizid; ?>';
        <?php $_SESSION['quizid'] = $quizid; ?>
        document.getElementById('quizLink').value = quizLink;
    }

    function copyQuizLink() {
        var quizLinkElement = document.getElementById('quizLink');
        quizLinkElement.select();
        document.execCommand('copy');
        quizLinkElement.setSelectionRange(0, 0);
        alert('Quiz link copied to clipboard!');
    }
</script>
</body>
</html>