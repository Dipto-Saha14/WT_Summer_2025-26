<?php 
require_once "config.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web Tech Task</title>
</head>
<body>

   
    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green; bold;">Action completed: <?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>

    <h2>Add User Record</h2>
    <form action="process.php" method="POST">

        <label>Full Name:</label><br>
        <input type="text" name="fullname" required><br><br>

     
        <label>Email Address:</label><br>
        <input type="email" name="email" required><br><br>

       
        <label>Age:</label><br>
        <input type="number" name="age" min="1" max="100" required><br><br>

       
        <label>Date of Birth:</label><br>
        <input type="date" name="dob" required><br><br>

   
        <label>User Role:</label><br>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Editor">Editor</option>
            <option value="Subscriber">Subscriber</option>
        </select><br><br>

        <button type="submit" name="add_user">Add User</button>
    </form>

    <hr>

    <h2>Upload Image</h2>
    <form action="process.php" method="POST" enctype="multipart/form-data">
  
        <label>Select File:</label><br>
        <input type="file" name="userImage" accept="image/*" required><br><br>
        <button type="submit" name="upload_image">Upload</button>
    </form>

    <hr>

  
    <h2>Search Users</h2>
    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Search by name...">
        <button type="submit">Filter</button>
        <a href="index.php">Reset</a>
    </form>

    <hr>

  
    <h2>User List</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>DOB</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php
    
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = "%" . $_GET['search'] . "%";
            $stmt = $conn->prepare("SELECT * FROM Users WHERE fullname LIKE ?");
            $stmt->bind_param("s", $search);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT id, fullname, email, age, dob, role FROM Users WHERE fullname IS NOT NULL";
            $result = $conn->query($sql);
        }

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['fullname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['age'] . "</td>";
                echo "<td>" . $row['dob'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>
                        <!-- Inline Form for Update -->
                        <form action='process.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                            <input type='text' name='fullname' value='" . $row['fullname'] . "' required>
                            <input type='email' name='email' value='" . $row['email'] . "' required>
                            <button type='submit' name='update_user'>Update</button>
                        </form>
                        <!-- Inline Form for Delete -->
                        <form action='process.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_user'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No records found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>

</body>
</html>
