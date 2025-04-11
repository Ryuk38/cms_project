<?php
$servername = "localhost"; 
$username = "root";
$password = "root";
$dbname = "cms";
$port = "3307"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $usnm = $_POST['username'];
    $psswd = $_POST['password'];
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
      echo("Connection failed: " . $conn->connect_error);
    } else {
      $sql = "SELECT id,user_type FROM login WHERE username='$usnm' AND pass='$psswd'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_type'] = $row['user_type'];
        header("location: dashboard.php");
      } else {
        echo ' <Script>alert("Incorrect username and password")</script>';
        echo '<script>window.location.href="login.php";</script>';
        exit();
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LMS | Login</title>
  <style>
    body {
      background-image: url("images/open-bg (2).jpg");
      background-size: cover;
      background-position: center;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
        Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue",
        sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
    }

    .login-container {
      background: rgba(0, 0, 0, 0.7);
      padding: 50px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .login-container h2 {
      text-align: center;
      font-size: 35px;
      margin-bottom: 40px;
    }

    .login-container label {
      display: block;
      margin-top: 20px;
      font-size: 18px;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 98%;
      padding: 12px;
      margin-top: 10px;
      border: none;
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 16px;
    }

    .login-container input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .login-container input:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.2);
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      margin-top: 30px;
      border: none;
      border-radius: 5px;
      background-color: #ff4500;
      /* Orange color */
      color: white;
      font-size: 20px;
      cursor: pointer;
    }

    .login-container button:hover {
      background-color: #ff6347;
    }

    .login-container p {
      text-align: right;
      margin-top: 20px;
      cursor: pointer;
      font-size: 16px;
    }

    .login-container a {
      color: white;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h2>Login</h2>
    <form action="login.php" method="post">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Type your username" required />
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Type your password" required />
      <a href="forgot_password.php">
        <p>Forgot Password?</p>
      </a>
      <button type="submit">Login</button>
    </form>
    <a href="register.php">
      <p style="margin-top: 30px; text-align: center">Or Sign Up</p>
    </a>
  </div>
</body>

</html>