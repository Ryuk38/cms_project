<?php
session_start();
if (!isset($_SESSION['id'])) {
  echo '<script>alert("Please Login to Continue"); window.location.href="login.php";</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LMS | Dashboard</title>
  <style>
    body {
      background-image: url("images/open-bg (2).jpg");
      background-size: cover;
      background-position: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: white;
    }

    .dashboard-container {
      background: rgba(0, 0, 0, 0.7);
      padding: 40px;
      border-radius: 10px;
      width: 50%;
      max-width: 800px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .dashboard-container h2 {
      font-size: 30px;
      margin-bottom: 20px;
    }

    .dashboard-container p {
      margin-bottom: 30px;
      font-size: 18px;
    }

    .dashboard-container a {
      display: block;
      margin-bottom: 15px;
      padding: 12px 24px;
      background-color: #ff4500;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    .dashboard-container a:hover {
      background-color: #ff6347;
    }
  </style>
</head>

<body>
  <div class="dashboard-container">
    <?php
    $servername = "127.0.0.1";
    $username = "root";
   $password = "root";
   $dbname = "cms";
   $port = "3307";

    $id = $_SESSION['id'];

    $conn = new mysqli($servername, $username, $password, $dbname, (int)$port);
    
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } else {

      $user_sql = "SELECT user_type FROM login WHERE id=$id";
      $result = $conn->query($user_sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //Students
        if ($row['user_type'] === '1') {
          $sql = "SELECT student_info.First_Name FROM student_info JOIN login ON login.id = student_info.stud_id WHERE stud_id=$id";

          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Welcome to Your Dashboard, " . $row["First_Name"] . "!</h2>";
            echo '<p>This is where you can manage your courses:</p>
            <a href="course.php">View Courses</a>
            <a href="enrolled_course.php">Enrolled Courses</a>
            <a href="logout.php">Logout</a>';
          } else {
            echo "<h2>Welcome to Your Dashboard </h2>";
            echo '<p>This is where you can manage your courses:</p>
            <a href="course.php">View Courses</a>
            <a href="enrolled_course.php">Enrolled Courses</a>
            <a href="logout.php">Logout</a>';
          }
        }
        //teachers

        elseif ($row['user_type']  === '2') {
          $sql = "SELECT teacher_info.First_Name FROM teacher_info JOIN login ON login.id = teacher_info.tr_id WHERE tr_id=$id";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Welcome to Your Dashboard, " . $row["First_Name"] . "!</h2>";
            echo '<a href="course.php">View Courses</a>';
            echo '<a href="course.php">Assigned Courses</a>';
            echo '<a href="enrolled_course.php">Enrolled Students</a>';
            echo '<a href="logout.php">Logout</a>';
          } 
          else {
            echo "<h2>Welcome to Your Dashboard </h2>";
            echo '<p>This is where you can manage your courses:</p>
            <a href="course.php">View Courses</a>
            <a href="course.php">Assigned Courses</a> 
            <a href="enrolled_course.php">Enrolled Students</a>
            <a href="logout.php">Logout</a>';
          }
        } else
        //admin
        {
          echo "<h2>Welcome to Your Admin Panel</h2><br>";
          echo '
            <a href="new_course.php">Add Courses</a>
            <a href="course.php">View Courses</a>
            <a href="logout.php">Logout</a>';
        }
      }
    }
    ?>

  </div>
</body>

</html>