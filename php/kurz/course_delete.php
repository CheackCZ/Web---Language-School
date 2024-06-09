<?php
    $id = $_POST["id"];

    // Include connection file
    include("../../inc/connection.php");

    // Prepare and bind
    $stmt = $conn->prepare("delete from kurz where id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute() === true) {
        echo "deleted";
        header("location: ../dashes/admin_dash.php");
    } else {
        echo "error".$conn->error;
    }

    // Close stmt and conn
    $stmt->close();
    $conn->close();
?>