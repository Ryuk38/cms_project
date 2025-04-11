<?php
session_start();
if (!isset($_SESSION['id'])) {
  echo '<script>alert(" Please Login to Continue"); window.location.href="login.php";</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LMS | Explore Courses</title>
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
      height: 100vh;
      color: white;
    }

    .dashboard-container {
      background: rgba(0, 0, 0, 0.7);
      padding: 40px;
      border-radius: 10px;
      width: 80%;
      max-width: 800px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      overflow-y: auto;
      max-height: calc(100vh - 200px);
    }

    .dashboard-container h2 {
      text-align: center;
      font-size: 30px;
      margin-bottom: 30px;
    }

    .course {
      background: rgba(255, 255, 255, 0.1);
      padding: 20px;
      margin-top: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .course h3 {
      font-size: 24px;
      margin-bottom: 10px;
      flex-grow: 1;
    }

    .course a {
      display: inline-block;
      padding: 8px 16px;
      background-color: #ff4500;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }

    .course a:hover {
      background-color: #ff6347;
    }

    .back-link {
      display: block;
      text-align: center;
      font-size: 18px;
      color: #ff4500;
      text-decoration: none;
    }

    .back-link:hover {
      color: #ff6347;
    }
  </style>
</head>

<body>
  <div class="dashboard-container">
    <h2>Explore Courses</h2>
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
    <?php
    $id = $_SESSION['id'];
    $servername = "127.0.0.1";
    $username = "root";
   $password = "root";
   $dbname = "cms";
   $port = "3307";

   $conn = new mysqli($servername, $username, $password, $dbname, (int)$port);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } else {
      $sql = "SELECT course_id, course_name FROM course";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<div class="course">';
          echo '<h3>' . $row["course_name"] . '</h3>';
          echo '<a href="course_details.php?course_id=' . urlencode($row[  "course_id"]) . '">View Details</a>';
          echo '</div>';
        }
      }
      else
      {
        echo '<div class="course">';
        echo '<h3>No Courses Available</h3>';
        echo '</div>';
      }
      $conn->close();
    }
    ?>
  </div>
</body>

</html>