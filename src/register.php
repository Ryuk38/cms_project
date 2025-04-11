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
        $user_type = $_POST['user_type']; 
        $user_type = ($user_type === 'student') ? 1 : 2;

        $conn = new mysqli($servername, $username, $password, $dbname,$port);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {

            $check = "SELECT username FROM login where username='$usnm'";
            $result = $conn->query($check);
            if ($result->num_rows > 0) {
                echo '<script>alert("Username already exists. Choose a different username!!!"); window.location.href="register.php";</script>';
            } else {
                $sql = $conn->prepare("INSERT INTO login(username,pass,user_type)  VALUES (?, ?, ?)");
                $sql->bind_param("ssi", $usnm, $psswd, $user_type);
                if ($sql->execute()) {
                    echo '<script>alert("Registration successful."); window.location.href="login.php";</script>';
                } else {
                    echo '<script>alert("Error: ' . $stmt->error . '"); window.location.href="register.php";</script>';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Register</title>
    <style>
        body {
            background-image: url("images/open-bg (2).jpg");
            background-size: cover;
            background-position: center;
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .register-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            height: 550px;
            border-radius: 10px;
            width: 420px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .register-container h2 {
            text-align: center;
            font-size: 32px;
            margin-top: 38px;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .register-container label {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;    
        }

        .radio-group {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }

        .radio-option {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-direction: row;
            margin-right: 50px;
        }

        .radio-option input[type="radio"] {
            transform: scale(1.2);
            margin: 0;
        }

        .radio-option label {
            font-size: 18px;
            font-weight: 500;
            line-height: 1;
            cursor: pointer;
            margin: 0; 
            font-family: "Poppins", sans-serif;
        }

        .register-container input[type="text"],
        .register-container input[type="password"] {
            width: 95%;
            padding: 14px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: none;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
            font-weight: 400;
        }

        .register-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .register-container input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.2);
        }

        .register-container button {
            width: 100%;
            padding: 14px;
            margin-top: 25px;
            border: none;
            border-radius: 6px;
            background-color: #ff4500;
            color: white;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-container button:hover {
            background-color: #ff6347;
        }

        .register-container p {
            text-align: center;
            margin-top: 40px;
            font-size: 16px;
            font-weight: 400;
        }

        .register-container a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <label>User Type</label>
            <div class="radio-group">
                <label class="radio-option">
                    <input type="radio" name="user_type" value="student" required>
                    Student
                </label>
                <label class="radio-option">
                    <input type="radio" name="user_type" value="teacher" required>
                    Teacher
                </label>
            </div>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Type your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Type your password" required>

            <button type="submit">Register</button>
        </form>
        <a href="login.php">
            <p>Already have an account? Login</p>
        </a>
    </div>
</body>

</html>