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

    // Set variables for user data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Hash the password for security
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SQL query to insert new user
    $sql = "INSERT INTO users (firstname, lastname, email, username, password)
            VALUES (?, ?, ?, ?, ?)";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters to the prepared statement
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $username, $password);
    
    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect the user to their own page after registration
        $_SESSION['username'] = $username;
        $x = $_POST['username'];
			  header("Location: view.php?username=$x");
			  exit();
    } 
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Registration Page</title>
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body>
  <header class="header">
		<a href="main.php" class="logo"><h2>Quizziz</h2></a>
		<div class="header__buttons">
			<a href="login.php" class="header__button">Log in</a>
		</div>
	</header>
    <div class="container">
      <form class="form" id="register-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h2>Create Account</h2>
        <div class="form-control">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" required>
        </div>
        <div class="form-control">
          <label for="lastname">Last Name</label>
          <input type="text" id="lastname" name="lastname" required>
        </div>
        <div class="form-control">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-control">
          <label for="username">User Name</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-control">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit" name="submit">Create Account</button>
        </div>
      </form>
    </div>
  </body>
</html>