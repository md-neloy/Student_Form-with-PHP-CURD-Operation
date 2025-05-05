<?php
include 'db.php';

// Insert operation
if (isset($_POST['add'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $batch = $_POST['batch'];
    $department = $_POST['department'];

    $insert_sql = "INSERT INTO students (student_id, name, batch, department)
                   VALUES ('$student_id', '$name', '$batch', '$department')";
    $conn->query($insert_sql);
    header("Location: index.php");
}

// Delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM students WHERE id=$id";
    $conn->query($delete_sql);
    header("Location: index.php");
}

// Fetch record for edit
$edit_mode = false;
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_sql = "SELECT * FROM students WHERE student_id=$id";
    $result = $conn->query($edit_sql);
    $edit_data = $result->fetch_assoc();
    $edit_mode = true;
}

// Update operation
if (isset($_POST['update'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $batch = $_POST['batch'];
    $department = $_POST['department'];

    $update_sql = "UPDATE students 
                   SET student_id='$student_id', name='$name', batch='$batch', department='$department' 
                   WHERE student_id=$student_id";
    $conn->query($update_sql);
    header("Location: index.php");
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>University Student Form</title>
    <style>
        input, button { margin: 5px; padding: 8px; }
        table { margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 10px; }
    </style>
</head>
<body>

<h2><?= $edit_mode ? "Edit Student" : "Add New Student" ?></h2>

<form method="POST" action="index.php">
    <?php if ($edit_mode): ?>
        <!-- Hidden so the value is submitted -->
        <input type="hidden" name="student_id" value="<?= $edit_data['student_id'] ?>">
    <?php else: ?>
        <!-- Editable field for new student -->
        <input type="text" name="student_id" placeholder="Student ID" required>
    <?php endif; ?>

    <!-- Always visible, but read-only in edit mode -->
    <?php if ($edit_mode): ?>
        <input type="text" value="<?= $edit_data['student_id'] ?>" readonly>
    <?php endif; ?>

    <input type="text" name="name" placeholder="Full Name" value="<?= $edit_mode ? $edit_data['name'] : '' ?>" required><br>
    <input type="text" name="batch" placeholder="Batch" value="<?= $edit_mode ? $edit_data['batch'] : '' ?>"><br>
    <input type="text" name="department" placeholder="Department" value="<?= $edit_mode ? $edit_data['department'] : '' ?>"><br>
    
    <button type="submit" name="<?= $edit_mode ? 'update' : 'add' ?>">
        <?= $edit_mode ? 'Update Student' : 'Add Student' ?>
    </button>
</form>


<hr>

<h3>All Students</h3>
<table>
    <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Batch</th>
        <th>Department</th>
        <th>Actions</th>
    </tr>
    <?php
    $students = $conn->query("SELECT * FROM students ORDER BY student_id DESC");
    while ($row = $students->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['student_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['batch'] ?></td>
        <td><?= $row['department'] ?></td>
        <td>
            <a href="index.php?edit=<?= $row['student_id'] ?>">Edit</a> |
            <a href="index.php?delete=<?= $row['student_id'] ?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
