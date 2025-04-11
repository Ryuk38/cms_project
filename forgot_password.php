<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "cms";
$port=3307;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['username'])) {
    $conn = new mysqli($servername, $username, $password, $dbname,$port);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } else {
      $user_name = $_POST['username'];
      $sql = "SELECT pass FROM login where username='$user_name'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<script>
                alert("Password:' . $row["pass"] . '");
                window.location.href="login.php";
              </script>';
      } else {
        echo '<script>alert("Username not found");
                    window.location.href="forgot_password.php";
            </script>';
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
  <title>LMS | Forgot Password</title>
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

    .forgot-container {
      background: rgba(0, 0, 0, 0.7);
      padding: 50px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .forgot-container h2 {
      text-align: center;
      font-size: 35px;
      margin-bottom: 40px;
    }

    .forgot-container label {
      display: block;
      margin-top: 20px;
      font-size: 18px;
    }

    .forgot-container input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      border: none;
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 16px;
    }

    .forgot-container input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .forgot-container input:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.2);
    }

    .forgot-container button {
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

    .forgot-container button:hover {
      background-color: #ff6347;
      /* Lighter orange */
    }

    .forgot-container p {
      text-align: center;
      margin-top: 30px;
      font-size: 18px;
    }

    .forgot-container a {
      color: white;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="forgot-container">
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Type your username" required />

      <button type="submit">Submit</button>
    </form>
    <a href="login.php">
      <p style="margin-top: 30px; text-align: center">Back to Login</p>
    </a>
  </div>
</body>

</html>