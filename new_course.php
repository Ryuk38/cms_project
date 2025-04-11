<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo '<script>alert("Please login to continue."); window.location.href="login.php";</script>';
    exit();
}

$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "cms";
$port = "3307";

$conn = new mysqli($servername, $username, $password, $dbname, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ====== DELETE COURSE ======
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM course WHERE course_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo '<script>alert("Course deleted successfully."); window.location.href="new_course.php";</script>';
        exit();
    } else {
        echo '<script>alert("Failed to delete course.");</script>';
    }
    $stmt->close();
}

// ====== ADD COURSE ======
if (isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'] ?? '';
    $course_desc = $_POST['course_description'] ?? '';
    $syllabus = $_POST['course_syllabus'] ?? '';
    $teacher_id = $_POST['teacher_id'] ?? null;

    if (!empty($course_name) && !empty($course_desc) && !empty($syllabus)) {
        if (!empty($teacher_id)) {
            $stmt = $conn->prepare("INSERT INTO course (course_name, course_desc, syllabus, tr_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $course_name, $course_desc, $syllabus, $teacher_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO course (course_name, course_desc, syllabus) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $course_name, $course_desc, $syllabus);
        }

        if ($stmt->execute()) {
            echo '<script>alert("Course added successfully."); window.location.href="new_course.php";</script>';
            exit();
        } else {
            echo '<script>alert("Error: ' . $stmt->error . '");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("All course fields are required.");</script>';
    }
}

// ====== FETCH DATA ======
$teachers_dropdown = $conn->query("SELECT * FROM teacher_info");

$all_courses = $conn->query("
    SELECT c.course_id, c.course_name, c.course_desc, c.syllabus, t.First_Name AS teacher_name 
    FROM course c 
    LEFT JOIN teacher_info t ON c.tr_id = t.tr_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Courses & Teachers</title>
  <style>
    body {
      background-image: url("images/open-bg (2).jpg");
      background-size: cover;
      background-position: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      color: white;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      background: rgba(0, 0, 0, 0.7);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      font-size: 28px;
      margin-bottom: 25px;
    }

    form label, form input, form textarea, form select, button {
      display: block;
      width: 100%;
      font-size: 16px;
      margin-bottom: 15px;
    }

    input[type="text"], textarea, select {
      padding: 10px;
      border-radius: 5px;
      border: none;
      background: rgba(255,255,255,0.9);
      color: #000;
    }

    button {
      padding: 12px;
      border: none;
      background: #ff4500;
      color: white;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #ff6347;
    }

    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    th {
      background: rgba(255,255,255,0.1);
    }

    a.delete-btn {
      color: #ff4d4d;
      text-decoration: none;
      font-weight: bold;
    }

    .section {
      margin-top: 50px;
    }

    .back-link {
      text-align: center;
      margin-top: 30px;
      display: block;
      color: #ff4500;
      font-weight: bold;
      text-decoration: none;
    }

    .back-link:hover {
      color: #ff6347;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Add New Course</h2>
    <form method="POST">
      <label>Course Name:</label>
      <input type="text" name="course_name" required>

      <label>Course Description:</label>
      <textarea name="course_description" rows="3" required></textarea>

      <label>Syllabus:</label>
      <textarea name="course_syllabus" rows="3" required></textarea>

      <label>Assign Teacher:</label>
      <select name="teacher_id">
        <option value="">-- Select Teacher --</option>
        <?php while ($row = $teachers_dropdown->fetch_assoc()): ?>
          <option value="<?= $row['tr_id'] ?>"><?= htmlspecialchars($row['First_Name']) ?></option>
        <?php endwhile; ?>
      </select>

      <button type="submit" name="add_course">Add Course</button>
    </form>

    <div class="section">
      <h2>All Courses</h2>
      <table>
        <thead>
          <tr>
            <th>Course Name</th>
            <th>Description</th>
            <th>Syllabus</th>
            <th>Teacher</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $all_courses->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['course_name']) ?></td>
              <td><?= htmlspecialchars($row['course_desc']) ?></td>
              <td><?= htmlspecialchars($row['syllabus']) ?></td>
              <td><?= $row['teacher_name'] ? htmlspecialchars($row['teacher_name']) : 'Not Assigned' ?></td>
              <td><a class="delete-btn" href="?delete=<?= $row['course_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
  </div>
</body>
</html>
