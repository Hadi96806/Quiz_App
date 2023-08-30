<?php
  if(isset($_POST["submit"])){
    // Start the session
    session_start();

    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "quizapp";
    // Get the username and password from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $conn = new mysqli($server, $user, $pass, $db);

    // Check for errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Build the SQL query
    $sql = "SELECT * FROM users WHERE username='$username'";
    // Execute the query
    $result = $conn->query($sql);

    // Check if the user is found
    if ($result->num_rows > 0) {
        // User found, set session variables and redirect to the dashboard
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        // Verify the password
        if(password_verify($password, $hashed_password)){
          // Set session variables and redirect to home page
          $_SESSION['username'] = $username;
          $x = $_POST['username'];
          $sql = "SELECT id FROM users WHERE username = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s", $x);
          $stmt->execute();
          $stmt->bind_result($userid);
          $stmt->fetch();
          $stmt->close();
          $_SESSION["userid"] = $userid;
          header("Location: view.php?username=$x");
          exit();
        }  
        else {
          $error_message = "Invalid password!";
        }
    } 
    else {
        // User not found, display an error message
        $error_message = "Invalid username!";
    }
    // Close the database connection
    $conn->close();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body>
  <header class="header">
		<a href="main.php" class="logo"><h2>Quizziz</h2></a>
		<div class="header__buttons">
			<a href="register.php" class="header__button">Sign up</a>
		</div>
	</header>
    <div class="container">
      <form class="form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <h2>Login</h2>
        <div class="form-control">
          <label for="username">Username</label>
          <input type="username" id="username" name="username" required>
        </div>
        <div class="form-control">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <br><br>
          <button type="submit" name="submit">Login</button>
          </form>
          <div class="form-control error">
              <?php 
                if(isset($error_message)){
                  echo $error_message;
                }
              ?>
          </div>
        </div>
    </div>
  </body>
</html>