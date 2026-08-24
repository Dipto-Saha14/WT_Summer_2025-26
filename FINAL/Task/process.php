<?php
require_once "config.php";


if (isset($_POST['add_user'])) {
    $name  = $_POST['fullname'];
    $email = $_POST['email'];
    $age   = $_POST['age'];
    $dob   = $_POST['dob'];
    $role  = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO Users (fullname, email, age, dob, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $email, $age, $dob, $role);
    $stmt->execute();
    $stmt->close();
    
    header("Location: index.php?msg=added");
    exit();
}


if (isset($_POST['upload_image'])) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $imgData = addslashes(file_get_contents($_FILES['userImage']['tmp_name']));
        $imageProperties = getimagesize($_FILES['userImage']['tmp_name']);
        $mime = $imageProperties['mime'];
        
        $sql = "INSERT INTO Users (imageType, imageData) VALUES ('{$mime}', '{$imgData}')";
        $conn->query($sql);
        
        header("Location: index.php?msg=uploaded");
        exit();
    }
}


if (isset($_POST['update_user'])) {
    $id    = $_POST['user_id'];
    $name  = $_POST['fullname'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE Users SET fullname=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?msg=updated");
    exit();
}


if (isset($_POST['delete_user'])) {
    $id = $_POST['user_id'];

    $stmt = $conn->prepare("DELETE FROM Users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?msg=deleted");
    exit();
}
?>
