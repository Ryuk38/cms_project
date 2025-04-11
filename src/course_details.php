<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_type'])) {
  echo '<script>alert("Please Login to Continue"); window.location.href="login.php";</script>';
  exit();
}

$user_id = $_SESSION['id'];
$user_type = $_SESSION['user_type']; // 0 = Admin, 1 = Student, 2 = Teacher

$course_id = $_GET['course_id'] ?? null;

if (!$course_id || !is_numeric($course_id)) {
  echo '<script>alert("Invalid Course ID"); window.location.href="explore_courses.php";</script>';
  exit();
}

$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "cms";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch course details
$stmt = $conn->prepare("
  SELECT c.course_name, c.course_desc, c.syllabus, c.tr_id, t.First_Name AS teacher_name
  FROM course c
  LEFT JOIN teacher_info t ON c.tr_id = t.tr_id
  WHERE c.course_id = ?
");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo '<script>alert("Course not found."); window.location.href="explore_courses.php";</script>';
  exit();
}

$course = $result->fetch_assoc();
$stmt->close();

// Check if student is already enrolled
$is_enrolled = false;
if ($user_type == 1) {
  $check_stmt = $conn->prepare("SELECT * FROM course_enrolled WHERE stud_id = ? AND course_id = ?");
  $check_stmt->bind_param("ii", $user_id, $course_id);
  $check_stmt->execute();
  $check_result = $check_stmt->get_result();

  if ($check_result->num_rows > 0) {
    $is_enrolled = true;
  }
  $check_stmt->close();
}

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && $user_type == 1 && !$is_enrolled) {
  $stud_id = $user_id;
  $tr_id = $course['tr_id'];

  $insert_stmt = $conn->prepare("INSERT INTO course_enrolled (stud_id, course_id, tr_id) VALUES (?, ?, ?)");
  $insert_stmt->bind_param("iii", $stud_id, $course_id, $tr_id);
  if ($insert_stmt->execute()) {
    echo '<script>alert("Enrolled successfully!"); window.location.href="enrolled_course.php";</script>';
    exit();
  } else {
    echo '<script>alert("Enrollment failed. Try again later.");</script>';
  }
  $insert_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Course Details</title>
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

    .detail {
      background: rgba(255, 255, 255, 0.1);
      padding: 20px;
      margin-top: 20px;
      border-radius: 8px;
    }

    .detail h3 {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .detail p {
      font-size: 18px;
      margin-top: 8px;
      line-height: 1.5;
    }

    .back-link {
      display: block;
      text-align: center;
      font-size: 18px;
      color: #ff4500;
      text-decoration: none;
      margin-top: 30px;
    }

    .back-link:hover {
      color: #ff6347;
    }

    .enroll-form {
      text-align: center;
      margin-top: 30px;
    }

    .enroll-form button {
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      font-size: 18px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .enroll-form button:hover {
      background-color: #218838;
    }

    .already-enrolled {
      text-align: center;
      margin-top: 30px;
      color: #00ffcc;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h2>Course Details</h2>

    <div class="detail">
      <h3>Course Name:</h3>
      <p><?= htmlspecialchars($course['course_name']) ?></p>
    </div>

    <div class="detail">
      <h3>Description:</h3>
      <p><?= htmlspecialchars($course['course_desc']) ?></p>
    </div>

    <div class="detail">
      <h3>Syllabus:</h3>
      <p><?= htmlspecialchars($course['syllabus']) ?></p>
    </div>

    <div class="detail">
      <h3>Assigned Teacher:</h3>
      <p><?= $course['teacher_name'] ? htmlspecialchars($course['teacher_name']) : 'Not Assigned' ?></p>
    </div>

    <?php if ($user_type == 1 && !$is_enrolled): ?>
      <form method="post" class="enroll-form">
        <button type="submit" name="enroll">Enroll in Course</button>
      </form>
    <?php elseif ($user_type == 1 && $is_enrolled): ?>
      <p class="already-enrolled">✅ You are already enrolled in this course.</p>
    <?php endif; ?>

    <a class="back-link" href="course.php">← Back to Explore Courses</a>
  </div>
</body>
</html>
