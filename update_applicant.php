<?php
// Include the database configuration file
require 'dbConfig.php';

$applicant = null;

// Check if we are updating an applicant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applicant_id'])) {
    $applicant_id = (int)$_POST['applicant_id'];

    // Fetch the current details of the applicant
    $sql = "SELECT * FROM applicants WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $applicant_id]);
    $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the form is submitted, update the applicant
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $date_of_application = trim($_POST['date_of_application']);
        $years_of_experience = trim($_POST['years_of_experience']);
        $highest_educ_attainment = trim($_POST['highest_educ_attainment']);

        // Update the applicant in the database
        $sql = "UPDATE applicants SET name = :name, phone = :phone, email = :email, 
                date_of_application = :date_of_application, years_of_experience = :years_of_experience, 
                highest_educ_attainment = :highest_educ_attainment WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email,
            ':date_of_application' => $date_of_application,
            ':years_of_experience' => $years_of_experience,
            ':highest_educ_attainment' => $highest_educ_attainment,
            ':id' => $applicant_id,
        ]);

        // Redirect back to the index page or display a success message
        header("Location: index.php?message=Applicant updated successfully");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Applicant</title>
</head>
<body>
    <h2>Update Applicant</h2>
    <?php if ($applicant): ?>
        <form action="update_applicant.php" method="post">
            <input type="hidden" name="applicant_id" value="<?php echo $applicant['id']; ?>">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $applicant['name']; ?>" required><br>
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $applicant['phone']; ?>" required><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $applicant['email']; ?>" required><br>
            <label>Date of Application:</label>
            <input type="date" name="date_of_application" value="<?php echo $applicant['date_of_application']; ?>" required><br>
            <label>Years of Experience:</label>
            <input type="number" name="years_of_experience" value="<?php echo $applicant['years_of_experience']; ?>" required><br>
            <label>Highest Educational Attainment:</label>
            <input type="text" name="highest_educ_attainment" value="<?php echo $applicant['highest_educ_attainment']; ?>" required><br>
            <button type="submit" name="action" value="update">Update Applicant</button>
        </form>
    <?php else: ?>
        <p>Applicant not found.</p>
    <?php endif; ?>
</body>
</html>