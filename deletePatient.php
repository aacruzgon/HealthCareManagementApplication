<!-- 
Programmer Name: 02

File Purpose:
This PHP script allows users to delete a patient from the database and view all patient records with sorting options.
It includes:
- Patient Deletion: Provides functionality to delete a patient by their OHIP number.
- Sorting: Allows sorting patient records by first name, last name, or OHIP number in ascending or descending order.
- Data Fetching: Queries the database to display a table of patients, including their assigned doctor.

Detailed Code Notes:
- Validation: Ensures safe use of sorting options and checks the existence of an OHIP number before deletion.
- JavaScript Confirmation: Utilizes JavaScript for user confirmation before initiating deletion.
- Error and Success Feedback: Displays messages to inform the user about the status of deletion operations.
-->
<?php
// Include database connection
include 'connectdb.php';

$error = '';
$success = '';

// Initialize default values for sorting options
$sortField = 'lastname';
$order = 'ASC';

// Get sorting options from the form, if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sortField']) && isset($_POST['order'])) {
        $sortField = $_POST['sortField'] ?? 'lastname';
        $order = $_POST['order'] ?? 'ASC';

        // Ensure sortField and order are safe for use in SQL
        $sortField = in_array($sortField, ['firstname', 'lastname', 'ohip']) ? $sortField : 'lastname';
        $order = ($order === 'DESC') ? 'DESC' : 'ASC';
    }

    // Handle deletion if "delete_ohip" is set
    if (isset($_POST['delete_ohip'])) {
        $ohip = $_POST['delete_ohip'];
        // Check if the OHIP exists
        $checkQuery = "SELECT * FROM patient WHERE ohip = '$ohip'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // OHIP exists, proceed with deletion
            $deleteQuery = "DELETE FROM patient WHERE ohip = '$ohip'";
            if (mysqli_query($connection, $deleteQuery)) {
                $success = "Patient with OHIP number $ohip has been successfully deleted.";
            } else {
                $error = "Error deleting patient: " . mysqli_error($connection);
            }
        } else {
            $error = "Error: No patient found with OHIP number $ohip.";
        }
    }
}

// SQL query to fetch all patient details with the chosen sorting options
$query = "
    SELECT 
        patient.ohip, patient.firstname, patient.lastname, patient.birthdate, 
        patient.weight, patient.height,
        doctor.firstname AS doctor_firstname,
        doctor.lastname AS doctor_lastname
    FROM 
        patient
    LEFT JOIN 
        doctor ON patient.treatsdocid = doctor.docid
    ORDER BY 
        $sortField $order";

$result = mysqli_query($connection, $query);

// Check for query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Patient</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <h1>Delete Patient</h1>

    <!-- Display success or error message -->
    <?php if ($success): ?>
        <p class="success-message"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="error-message"><?= $error ?></p>
    <?php endif; ?>

    <!-- Sorting Form -->
    <form method="post">
        <div class="dropdown">
            <label for="sortField">Order by:</label>
            <select name="sortField" id="sortField">
                <option value="lastname" <?= $sortField == 'lastname' ? 'selected' : '' ?>>Last Name</option>
                <option value="firstname" <?= $sortField == 'firstname' ? 'selected' : '' ?>>First Name</option>
                <option value="ohip" <?= $sortField == 'ohip' ? 'selected' : '' ?>>OHIP</option>
            </select>

            <label for="order">Order:</label>
            <select name="order" id="order">
                <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
                <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
            </select>
        </div>
        <button type="submit">Sort</button>
    </form>

    <!-- Instruction for selection -->
    <div class="instruction">Select Row to Delete Patient</div>

    <!-- Table of patients -->
    <table>
        <thead>
            <tr>
                <th>OHIP</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birthdate</th>
                <th>Weight (kg)</th>
                <th>Height (m)</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr onclick="confirmDeletion('<?= htmlspecialchars($row['ohip']) ?>')">
                <td><?= htmlspecialchars($row['ohip']) ?></td>
                <td><?= htmlspecialchars($row['firstname']) ?></td>
                <td><?= htmlspecialchars($row['lastname']) ?></td>
                <td><?= htmlspecialchars($row['birthdate']) ?></td>
                <td><?= htmlspecialchars($row['weight']) ?> kg</td>
                <td><?= htmlspecialchars($row['height']) ?> m</td>
                <td><?= htmlspecialchars($row['doctor_firstname']) . ' ' . htmlspecialchars($row['doctor_lastname']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Hidden form for deletion -->
    <form id="deleteForm" method="post">
        <input type="hidden" id="delete_ohip" name="delete_ohip">
    </form>

    <script>
        function confirmDeletion(ohip) {
            if (confirm("Are you sure you want to delete this patient?")) {
                document.getElementById('delete_ohip').value = ohip;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
<?php mysqli_close($connection); ?>
</body>
</html>