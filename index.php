<?php
// Include the database configuration file
require 'dbConfig.php';

// Initialize variables for form data
$name = $phone = $email = $date_of_application = $years_of_experience = $highest_educ_attainment = "";
$errors = [];

// Initialize search variable
$search = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $date_of_application = trim($_POST['date_of_application']);
    $years_of_experience = trim($_POST['years_of_experience']);
    $highest_educ_attainment = trim($_POST['highest_educ_attainment']);

    // Simple validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($date_of_application)) {
        $errors[] = "Date of application is required.";
    }
    if (empty($years_of_experience)) {
        $errors[] = "Years of experience is required.";
    }
    if (empty($highest_educ_attainment)) {
        $errors[] = "Highest educational attainment is required.";
    }

    // If no errors, insert data into the database
    if (empty($errors)) {
        $sql = "INSERT INTO applicants (name, phone, email, date_of_application, years_of_experience, highest_educ_attainment) 
                VALUES (:name, :phone, :email, :date_of_application, :years_of_experience, :highest_educ_attainment)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email,
            ':date_of_application' => $date_of_application,
            ':years_of_experience' => $years_of_experience,
            ':highest_educ_attainment' => $highest_educ_attainment
        ]);

        // Redirect or display success message
        echo "<div class='success'>Application submitted successfully!</div>";
    }
}

// Fetch all applicants from the database or filtered by search
$applicants = [];
$sql = "SELECT * FROM applicants";

// Check if a search term is provided
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $sql .= " WHERE name LIKE :search"; // Add search condition
}

$sql .= " ORDER BY date_of_application DESC"; // Order by date of application
$stmt = $pdo->prepare($sql);

// Bind the search parameter if it exists
if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%'); // Use wildcard for partial matches
}

$stmt->execute();
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Professional Applicant Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] 
        {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
    background-color: #5cb85c; 
    color: white; 
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-left: 10px; 
}

button:hover {
    background-color: #4cae4c; 
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Add Applicant</h1>

        <?php
        // Display errors
        if (!empty($errors)) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo '</div>';
        }
        ?>

        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="date_of_application">Date of Application:</label>
            <input type="date" name="date_of_application" id="date_of_application" value="<?php echo htmlspecialchars($date_of_application); ?>" required>

            <label for="years_of_experience">Years of Experience:</label>
            <input type="number" name="years_of_experience" id="years_of_experience" value="<?php echo htmlspecialchars($years_of_experience); ?>" required>

            <label for="highest_educ_attainment">Highest Educational Attainment:</label>
            <input type="text" name="highest_educ_attainment" id="highest_educ_attainment" value="<?php echo htmlspecialchars($highest_educ_attainment); ?>" required>

            <input type="submit" value="Submit">
        </form>

        <!-- Search Form -->
        <form action="" method="get">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>" required>
            <button type="submit">Search</button>
        </form>

        <h2>Applicants List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Date of Application</th>
                <th>Years of Experience</th>
                <th>Highest Educational Attainment</th>
                <th>Action</th> 
            </tr>
            <?php if (count($applicants) > 0): ?>
                <?php foreach ($applicants as $applicant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['phone']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['date_of_application']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['years_of_experience']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['highest_educ_attainment']); ?></td>
                        <td>
                            <form action="delete_applicant.php" method="post" style="display:inline;">
                                <input type="hidden" name="applicant_id" value="<?php echo $applicant['id']; ?>">
                                <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this applicant?');">Delete</button>
                            </form>
                            <form action="update_applicant.php" method="post" style="display:inline;">
                                <input type="hidden" name="applicant_id" value="<?php echo $applicant['id']; ?>">
                                <button type="submit" class="update-button">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No applicants found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>