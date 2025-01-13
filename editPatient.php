<!-- 
Programmer Name: 02

File Purpose:
This PHP script allows updating the weight of a patient in the database. The weight can be entered in either kilograms or pounds, with automatic conversion to kilograms if necessary.

Detailed Code Notes:
- Unit Conversion: Converts weight from pounds to kilograms using the conversion factor (1 lb = 0.453592 kg) if the selected unit is pounds.
- Prepared Statements: Uses a prepared statement to securely update the patient's weight in the database.
- User Feedback: Displays success or error messages based on the outcome of the update operation.
- Form Handling: Captures and validates user inputs, including the OHIP number, weight, and unit selection.
-->

<?php
include 'connectdb.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ohip = $_POST['ohip'];
    $weight = $_POST['weight'];
    $unit = $_POST['unit'];

    // Convert pounds to kilograms if necessary
    if ($unit == 'lb') {
        $weight = $weight * 0.453592; // 1 pound = 0.453592 kg
    }

    // Prepare the SQL statement to update the patient's weight in kilograms
    $query = "UPDATE patient SET weight = ? WHERE ohip = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ds", $weight, $ohip);

    if ($stmt->execute()) {
        $successMessage = "Patient weight updated successfully!";
    } else {
        $errorMessage = "Error updating patient weight. Please try again.";
    }

    $stmt->close();
    $connection->close(); // Close the connection here only once
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient Weight</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Edit Patient Weight</h1>

    <!-- Display success or error messages -->
    <?php if ($successMessage): ?>
        <p class="success-message"><?= $successMessage ?></p>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <p class="error-message"><?= $errorMessage ?></p>
    <?php endif; ?>

    <form action="mainmenu.php?page=editPatient" method="POST">
        <div class="form-group">
            <label for="ohip">OHIP Number:</label>
            <input type="text" name="ohip" id="ohip" required>
        </div>
        <div class="form-group">
            <label for="weight">Weight:</label>
            <input type="number" name="weight" id="weight" step="0.1" required>
        </div>
        <div class="form-group">
            <label for="unit">Unit:</label>
            <select name="unit" id="unit">
                <option value="kg">Kilograms</option>
                <option value="lb">Pounds</option>
            </select>
        </div>
        <button type="submit" class="submit-button">Update Weight</button>
    </form>
</div>
</body>
</html>