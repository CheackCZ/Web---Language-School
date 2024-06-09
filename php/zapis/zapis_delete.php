<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];

        include("../../inc/connection.php");

        // Prepare and bind
        $stmt = $conn->prepare("DELETE FROM zapis WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute() === true) {
            header("location:../dashes/admin_dash.php");
        } else {
            echo "Error: " . $conn->error;
        }

        // Close stmt and conn
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid request.";
    }
?>