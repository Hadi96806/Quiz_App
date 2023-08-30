<?php
    session_start();
    if(isset($_POST["logout"])){
        session_destroy();
        header("Location: login.php");
        exit();
    }
    if(isset($_POST['result'])){
        header("Location: viewresults.php?username=$x");
		exit();
	}
    
    if(!isset($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    $x = $_SESSION['username'];
    
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "quizapp";
    $conn = new mysqli($server, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " .$conn->connect_error);
    }           
    if(!isset($_SESSION['quizid'])){
        header("Location: quizcreate.php?username=$x");
        exit();
    }           
    else{
        $quizid = $_SESSION['quizid'];
    }             
    
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $x);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();

    if(isset($_POST['view'])){
        $quizid = $_SESSION['quizid'];
		header("Location: questionsview.php?quizid=$quizid");
		exit();
	}

    if(isset($_POST['addQuestion'])){
        // Get the quiz question data from the form
        $question = $_POST['question'];
        $answer1 = $_POST['answer1'];
        $answer2 = $_POST['answer2'];
        $answer3 = $_POST['answer3'];
        $answer4 = $_POST['answer4'];
        $correct_answer = $_POST['correct-answer'];
        
        $stmt = $conn->prepare("SELECT `total` from `quizzes` WHERE idquiz = ?");
        $stmt->bind_param("s", $quizid);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $row = $resultSet->fetch_assoc();
        $total = (int)$row['total'];

        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO `questions`(`id`, `idquiz`, `question`, `choice_1`, `choice_2`, `choice_3`, `choice_4`, `answer`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss",$id, $quizid, $question, $answer1, $answer2, $answer3, $answer4, $correct_answer);    
        $stmt->execute();
        $stmt->close();

        $total = $total + 1;
        $stmt = $conn->prepare("UPDATE quizzes SET total = ? WHERE idquiz = ?");
        $stmt->bind_param("is", $total, $quizid);
        $stmt->execute();
        $stmt->close();

        echo '<script>alert("Your question is added !");</script>';
        header("Location: addquestions.php?quizid=$quizid");
       
    }
    $conn->close();     
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Your Quiz</title>
        <link rel="stylesheet" href="css/add.css">
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
            <form class="form" id="myform" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">  
                <h2>Enter Your Quiz Questions:</h2>
                <div class="form-control">  
                    <label for='question'>Question :</label>
                    <input type='text' id='question' name='question'><br><br>   
                </div>
                <div class="form-control"> 
                    <label for='a'>Answer 1:</label>
                    <input type='text' id='answer1' name='answer1' required><br><br>
                </div>
                <div class="form-control">
                    <label for='b'>Answer 2:</label>
                    <input type='text' id='answer2' name='answer2' required><br><br>
                </div>
                <div class="form-control">
                    <label for='c'>Answer 3:</label>
                    <input type='text' id='answer3' name='answer3' required><br><br>
                </div>
                <div class="form-control">
                    <label for='d'>Answer 4:</label>
                    <input type='text' id='answer4' name='answer4' required><br><br>
                </div>
                <div class="form-control">
                    <label for='correct'>Correct Answer:</label>
                    <select name='correct-answer' required>
                        <option value='answer1'>Answer 1</option>
                        <option value='answer2'>Answer 2</option>
                        <option value='answer3'>Answer 3</option>
                        <option value='answer4'>Answer 4</option>
                    </select>
                </div>
                <button type='submit' name='addQuestion' class='but'>Add Question</button>
            </form>
        </div>
        <center>
            <br>
            <form class="form" id="myform" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">  
                    <button type='submit' class='but' name='view'>View your quiz</button>
            </form>
        </center>
    </body>
</html>