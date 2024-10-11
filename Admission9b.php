<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate full name
    $fullName = trim($_POST["fullName"]);
    if (!preg_match("/^[a-zA-Z-' ]+$/", $fullName)) {
        $_SESSION['error'] = "Nom invalide.";
        header("Location: form_page.php");
        exit();
    }

    // Validate email
    $email = trim($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email invalide.";
        header("Location: form_page.php");
        exit();
    }

    // Validate phone
    $phone = trim($_POST["phone"]);
    if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $_SESSION['error'] = "Numéro de téléphone invalide.";
        header("Location: form_page.php");
        exit();
    }

    // Validate date of birth
    $dob = trim($_POST["dob"]);
    if (empty($dob) || !strtotime($dob) || strtotime($dob) > time()) {
        $_SESSION['error'] = "Date de naissance invalide.";
        header("Location: form_page.php");
        exit();
    }

    // Validate program
    $program = trim($_POST["program"]);
    if (empty($program)) {
        $_SESSION['error'] = "Veuillez sélectionner un programme.";
        header("Location: form_page.php");
        exit();
    }

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "database");
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("Erreur de connexion à la base de données.");
    }

    // Generate unique ID
    $uniqueID = uniqid($fullName, true);

    // Barcode generation
    require 'vendor/autoload.php';
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($uniqueID, $generator::TYPE_CODE_128);
    file_put_contents('barcodes/' . $uniqueID . '.png', $barcode);

    // Prepared statement to insert data
    $stmt = $conn->prepare("INSERT INTO registrations (fullName, email, phone, dob, program, uniqueID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullName, $email, $phone, $dob, $program, $uniqueID);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Inscription réussie.";
        header("Location: confirmation.php?uniqueID=" . $uniqueID);
        exit();
    } else {
        error_log("Error: " . $stmt->error);
        $_SESSION['error'] = "Erreur lors de l'inscription.";
        header("Location: form_page.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: form_page.php");
    exit();
}
?>