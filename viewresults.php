<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/results.css">
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
            <?php
                if(isset($_GET['quizid'])){
                    $quizid = $_GET['quizid'];
                    // Connect to the database
                    $servername = "localhost";
                    $user = "root";
                    $pass = "";
                    $dbname = "quizapp";
                    $conn = new mysqli($servername, $user, $pass, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT name, result FROM results WHERE idquiz = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $quizid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    
                    if (!$result) {
                        die("Query failed: " . $conn->error);
                    }

                    // Output the results in an HTML table
                    if ($result->num_rows > 0) {
                        // Output the results in a table
                        echo "<table><tr><th colspan='2'><center>These are the results of participants</center></th></tr>";
                        echo "<tr><th><center>Name</center></th><th><center>Result</center></th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row["name"] . "</td><td>" . $row["result"] . "</td></tr>";
                        }
                        echo "</table>";
                    } 
                    else {
                        echo "No results found for quiz with ID $quizid";
                    }
                    // Close the database connection
                    $conn->close();
                }
                // Logout button handler
                if (isset($_POST['logout'])) {
                    // Destroy the session and redirect to login page
                    session_destroy();
                    header("Location: login.php");
                    exit();
                }
            ?>
    </body>
</html>