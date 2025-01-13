<!-- 
Programmer Name: 02

File Purpose:
This PHP script facilitates adding a new patient to the database. It includes functionality for:
- Validating form inputs, including ensuring the uniqueness of the OHIP number.
- Handling database interactions for patient addition.
- Fetching a list of doctors to display in a dropdown menu for assigning a doctor to the new patient.

Detailed Code Notes:
- Validation Logic: Ensures that the OHIP number is exactly 9 digits and checks positive values for weight and height.
- Error Handling: Displays appropriate error messages for validation failures or database errors.
- Dynamic Dropdown: Queries the database for available doctors to populate the dropdown list dynamically.
-->

<?php
// Include database connection
include 'connectdb.php';

// Initialize variables for error messages and form inputs
$error = '';
$ohip = $firstname = $lastname = $weight = $birthdate = $height = $treatsdocid = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ohip = $_POST['ohip'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $weight = $_POST['weight'];
    $birthdate = $_POST['birthdate'];
    $height = $_POST['height'];
    $treatsdocid = $_POST['treatsdocid'];

    // Validate OHIP length and positive values for weight and height
    if (strlen($ohip) !== 9) {
        $error = "Error: OHIP number must be exactly 9 characters.";
    } elseif ($weight <= 0) {
        $error = "Error: Weight must be a positive number.";
    } elseif ($height <= 0) {
        $error = "Error: Height must be a positive number.";
    } else {
        // Check if OHIP is unique
        $checkQuery = "SELECT * FROM patient WHERE ohip = '$ohip'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = "Error: The OHIP number is already in use. Please enter a unique OHIP number.";
        } else {
            // Insert new patient into the database
            $insertQuery = "
                INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid)
                VALUES ('$ohip', '$firstname', '$lastname', '$weight', '$birthdate', '$height', '$treatsdocid')
            ";
            if (mysqli_query($connection, $insertQuery)) {
                echo "<p class='success-message'>Patient added successfully!</p>";
                // Clear form inputs
                $ohip = $firstname = $lastname = $weight = $birthdate = $height = $treatsdocid = '';
            } else {
                $error = "Error adding patient: " . mysqli_error($connection);
            }
        }
    }
}

// Fetch list of doctors for dropdown
$doctorQuery = "SELECT docid, firstname, lastname FROM doctor";
$doctorResult = mysqli_query($connection, $doctorQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add New Patient</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <h1>Add New Patient</h1>

    <?php if ($error): ?>
        <p class="error-message"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>OHIP Number:</label>
            <input type="text" name="ohip" value="<?= htmlspecialchars($ohip) ?>" required pattern="\d{9}" title="OHIP number must be exactly 9 digits">
        </div>
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>" required>
        </div>
        <div class="form-group">
            <label>Birthdate:</label>
            <input type="date" name="birthdate" value="<?= htmlspecialchars($birthdate) ?>" required>
        </div>
        <div class="form-group">
            <label>Weight (kg):</label>
            <input type="number" name="weight" value="<?= htmlspecialchars($weight) ?>" step="0.1" min="0.1" required>
        </div>
        <div class="form-group">
            <label>Height (m):</label>
            <input type="number" name="height" value="<?= htmlspecialchars($height) ?>" step="0.01" min="0.01" required>
        </div>
        <div class="form-group">
            <label>Assign Doctor:</label>
            <select name="treatsdocid" required>
                <option value="">Select a doctor</option>
                <?php while ($row = mysqli_fetch_assoc($doctorResult)): ?>
                    <option value="<?= htmlspecialchars($row['docid']) ?>" <?= $treatsdocid == $row['docid'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="submit-button">Add Patient</button>
    </form>
</div>

<?php mysqli_close($connection); ?>
</body>
</html>