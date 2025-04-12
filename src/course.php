<?php
session_start();
if (!isset($_SESSION['id'])) {
  echo '<script>alert("Please Login to Continue"); window.location.href="login.php";</script>';
  exit();
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
    }

    .course h3 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .course p {
      font-size: 16px;
      margin-bottom: 10px;
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
      margin-top: 20px;
    }

    .back-link:hover {
      color: #ff6347;
    }
  </style>
</head>

<body>
  <div class="dashboard-container">
    <h2>Explore Your Courses</h2>
    <a class="back-link" href="dashboard.php">Back to Dashboard</a>
    <?php
    $id = $_SESSION['id']; // teacher ID
    $servername = "127.0.0.1";
    $username = "root";
    $password = "root";
    $dbname = "cms";
    $port = 3307;

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT course_id, course_name, course_desc, syllabus FROM course WHERE tr_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '<div class="course">';
        echo '<h3>' . htmlspecialchars($row["course_name"]) . '</h3>';
        echo '<p><strong>Description:</strong> ' . nl2br(htmlspecialchars($row["course_desc"])) . '</p>';
        echo '<p><strong>Syllabus:</strong> ' . nl2br(htmlspecialchars($row["syllabus"])) . '</p>';
        echo '<a href="course_details.php?course_id=' . urlencode($row["course_id"]) . '">View Details</a>';
        echo '</div>';
      }
    } else {
      echo '<div class="course">';
      echo '<h3>No Courses Assigned</h3>';
      echo '</div>';
    }

    $stmt->close();
    $conn->close();
    ?>
  </div>
</body>

</html>
