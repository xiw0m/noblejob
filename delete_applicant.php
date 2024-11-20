<?php
// Include the database configuration file
require 'dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applicant_id'])) {
    $applicant_id = (int)$_POST['applicant_id'];

    // Prepare and execute the delete statement
    $sql = "DELETE FROM applicants WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $applicant_id]);

    // Redirect back to the index page or display a success message
    header("Location: index.php?message=Applicant deleted successfully");
    exit();
} else {
    // Redirect to index page if not a POST request
    header("Location: index.php");
    exit();
}
?>