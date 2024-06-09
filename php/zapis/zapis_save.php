<?php
    // Připojení k dba
    include("../../inc/connection.php");

    // Příprava příkazu: "prepared statement" .. pro příkaz insert, update, delete
    $sql = "insert into zapis(student_id,kurz_id,datum_zacpisu) value(?,?,?)";
    $stmt = $conn->prepare($sql); // Kontrola a předklad insertu

    // Set parametry
    $student_id = $_POST["student_id"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
    $kurz_id = $_POST["kurz_id"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
    $datum_zacpisu = $_POST["datum_zapisu"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné

    // Bind parametry
    $stmt->bind_param("ssi", $student_id, $kurz_id, $datum_zapisu);  // Vloží se místo ?
    
    // Execute
    $stmt->execute();

    // Redirect back to index.php
    header("Location:../admin_dash.php");

    // Close stmt and conn
    $stmt->close();
    $conn->close();
?>