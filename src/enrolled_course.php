<?php
session_start();
if (!isset($_SESSION['id'])) {
    echo '<script>alert("Please login to continue."); window.location.href="login.php";</script>';
    exit();
}

$id = intval($_SESSION['id']);

$servername = "localhost"; 
$username = "root";
$password = "root"; 
$dbname = "cms"; 
$port = "3307"; 

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_sql = "SELECT user_type FROM login WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_type_row = $user_result->fetch_assoc();
$user_type = $user_type_row['user_type'];
$user_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>LMS | Enrolled Courses / Students</title>
  <style>
    body {
      background-image: url("images/open-bg (2).jpg");
      background-size: cover;
      background-position: center;
      font-family: system-ui, sans-serif;
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
      max-width: 900px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      overflow-y: auto;
      max-height: 80vh;
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
    }

    .course h3 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .course p, .course li {
      font-size: 16px;
    }

    .course a {
      display: inline-block;
      margin-top: 10px;
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
      margin-top: 20px;
      text-decoration: none;
    }

    .back-link:hover {
      color: #ff6347;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <?php if ($user_type == '1'): ?>
      <h2>My Enrolled Courses</h2>
      <?php
      $sql = "SELECT c.course_name, c.course_id 
              FROM course_enrolled ce 
              JOIN course c ON ce.course_id = c.course_id 
              WHERE ce.stud_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0):
        while ($course = $result->fetch_assoc()):
      ?>
          <div class="course">
            <h3><?= htmlspecialchars($course['course_name']) ?></h3>
            <a href="course_details.php?course_id=<?= urlencode($course['course_id']) ?>">View Details</a>
          </div>
      <?php endwhile; else: ?>
          <p>No enrolled courses found.</p>
      <?php endif;
      $stmt->close(); ?>

    <?php elseif ($user_type == '2'): ?>
      <h2>Students Enrolled in Your Courses</h2>
      <?php
      $sql_courses = "
        SELECT DISTINCT c.course_id, c.course_name 
        FROM course_enrolled ce 
        JOIN course c ON ce.course_id = c.course_id 
        WHERE ce.tr_id = ?";
      $stmt_courses = $conn->prepare($sql_courses);
      $stmt_courses->bind_param("i", $id);
      $stmt_courses->execute();
      $courses_result = $stmt_courses->get_result();

      if ($courses_result->num_rows > 0):
        while ($course = $courses_result->fetch_assoc()):
          $course_id = $course['course_id'];
          $course_name = $course['course_name'];
      ?>
          <div class="course">
            <h3><?= htmlspecialchars($course_name) ?></h3>
            <?php
            $sql_students = "
              SELECT s.First_Name, s.Last_Name 
              FROM course_enrolled ce 
              JOIN student_info s ON ce.stud_id = s.stud_id 
              WHERE ce.course_id = ?";
            $stmt_students = $conn->prepare($sql_students);
            $stmt_students->bind_param("i", $course_id);
            $stmt_students->execute();
            $students_result = $stmt_students->get_result();

            if ($students_result->num_rows > 0):
              echo "<ul>";
              while ($student = $students_result->fetch_assoc()):
                echo "<li>" . htmlspecialchars($student['First_Name'] . ' ' . $student['Last_Name']) . "</li>";
              endwhile;
              echo "</ul>";
            else:
              echo "<p>No students enrolled.</p>";
            endif;

            $stmt_students->close();
            ?>
          </div>
      <?php endwhile; else: ?>
        <p>No courses or students found.</p>
      <?php endif;

      $stmt_courses->close(); ?>
    <?php endif; ?>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>
