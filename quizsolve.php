<?php 
   session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quizizz</title>
    <link rel="stylesheet" href="css/solve.css">
</head>
<body>
<header class="header">
    <a href="main.php" class="logo"><h2>Quizziz</h2></a>
    <div class="header__buttons">
        <a href="register.php" class="header__button">Create your own Quiz</a>
    </div>
</header>
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
    $name = $_SESSION['name'];
    echo "<h1>Good Luck " . $name . "!</h1>";
    $i=0;
    $quizid = $_SESSION['quizid'];
    $sql = "SELECT id FROM quizzes WHERE idquiz = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $quizid);
    $stmt->execute();
    $stmt->bind_result($userid);
    $stmt->fetch();
    $stmt->close();

    // Select a random question from the database
    $sql = "SELECT * FROM questions where idquiz='$quizid'";
    $result = $conn->query($sql);
    $questions = array();

    while($row = mysqli_fetch_assoc($result)) {
        $questions[] = $row;
    }
    $sql = "SELECT answer FROM questions where 
    idquiz ='$quizid'";
    $result = $conn->query($sql);
    $answers = array();
    
    while($row = mysqli_fetch_assoc($result)) {
        $answers[] = $row;
    }
    $userAnswers = array();
    $question_num = 1;
?>
<form method="post" action="quizsolve.php">
    <?php 
        foreach($questions as $question): 
            $radioButtonName = 'q' . $question['idques'];
    ?>
    <div>
        <p>
            <?php 
            echo "<h3>Question " . $question_num . ":</h3>";
            echo $question['question']; ?>
        </p>
        <ul>
            <li><input type="radio" name="q<?php echo $question['idques']; ?>" value="choice_1"> <?php echo $question['choice_1']; ?></li>
            <li><input type="radio" name="q<?php echo $question['idques']; ?>" value="choice_2"> <?php echo $question['choice_2']; ?></li>
            <li><input type="radio" name="q<?php echo $question['idques']; ?>" value="choice_3"> <?php echo $question['choice_3']; ?></li>
            <li><input type="radio" name="q<?php echo $question['idques']; ?>" value="choice_4"> <?php echo $question['choice_4']; ?></li>
        </ul>
        <?php  
            // Check if the user selected an answer for this question
            if(isset($_POST[$radioButtonName])) {
                // If so, save the user's answer to the $userAnswers array
                $userAnswer = $_POST[$radioButtonName];
                $userAnswers[$i] = $userAnswer;
            }
        ?>
    </div>
    <?php 
        $question_num++;
        $i+=1;
        endforeach; 
    ?>
    <input type='submit' name='submit_quiz' value='Submit Quiz'>
</form>
<?php
    $stmt = $conn->prepare("SELECT `total` from `quizzes` WHERE idquiz = ?");
    $stmt->bind_param("s", $quizid);
    $stmt->execute();
    $resultSet = $stmt->get_result();
    $row = $resultSet->fetch_assoc();
    $total = (int)$row['total'];
    if(isset($_POST['submit_quiz'])) {
        $score = 0;
        // Loop through submitted answers and calculate score
        foreach ($answers as $key => $value) {
            if (isset($userAnswers[$key]) && substr($userAnswers[$key],-1) == substr($value['answer'],-1)) {
                $score++;
            }
        }
        // Insert score into database
        $stmt = $conn->prepare("INSERT INTO results (`id`, `idquiz`, `name`, `result`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $userid, $quizid, $name, $score);
        $stmt->execute();
        $stmt->close();

        $_SESSION['score'] = $score;
        $_SESSION['quizid'] = $quizid;
        $_SESSION['total'] = $total;
        header("Location: quizscore.php?name=$name");
        exit();
    }
    // Close database connection
    $conn->close();
?>
</body>
</html>