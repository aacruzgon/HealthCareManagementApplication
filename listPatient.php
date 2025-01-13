<!-- 
Programmer Name: 02

File Purpose:
This PHP script displays a list of patients with their details and associated doctor information.
It allows sorting the patient list by first or last name in ascending or descending order.
Additionally, it performs on-the-fly conversions for weight and height to display both metric and imperial units.

Detailed Code Notes:
- Sorting: Captures user preferences for sorting and applies them securely to the SQL query.
- Data Conversion:
  - Weight: Converts kilograms to pounds using the factor (1 kg = 2.20462 lbs).
  - Height: Converts meters to feet and inches, ensuring accurate breakdown of height values.
- Dynamic Table: Generates a table with patient information, including the calculated imperial values for weight and height.
- Error Handling: Validates the success of the SQL query and displays an appropriate error message if the query fails.
-->

<?php
// Include database connection
include 'connectdb.php';

// Initialize default values for sorting options
$sortField = 'lastname';
$order = 'ASC';

// Get sorting options from the form, if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sortField = $_POST['sortField'] ?? 'lastname';
    $order = $_POST['order'] ?? 'ASC';

    // Ensure sortField and order are safe for use in SQL
    $sortField = ($sortField === 'firstname') ? 'firstname' : 'lastname';
    $order = ($order === 'DESC') ? 'DESC' : 'ASC';
}

// SQL query to fetch all patient details along with their doctor’s name
$query = "
    SELECT 
        patient.ohip,
        patient.firstname,
        patient.lastname,
        patient.birthdate,
        patient.weight,
        patient.height,
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
    <title>Patient List</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <h1>Patients List</h1>

    <!-- Sorting Form -->
    <form method="post" class="sort-form">
    <div class="sort-title">Sort By:</div>
    <div class="dropdown-container">
        <select name="sortField" id="sortField">
            <option value="lastname" <?= $sortField == 'lastname' ? 'selected' : '' ?>>Last Name</option>
            <option value="firstname" <?= $sortField == 'firstname' ? 'selected' : '' ?>>First Name</option>
        </select>
        
        <select name="order" id="order">
            <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>Ascending</option>
            <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>Descending</option>
        </select>
    </div>
    <button type="submit" class="sort-button">Sort</button>
</form>

    <!-- Table of patients -->
    <table>
        <thead>
            <tr>
                <th>OHIP</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birthdate</th>
                <th>Doctor</th>
                <th>Weight (kg)</th>
                <th>Weight (lbs)</th>
                <th>Height (m)</th>
                <th>Height (ft-inches)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) {
                // Convert weight to lbs
                $weight_lbs = $row['weight'] * 2.20462;
                
                // Convert height in meters to feet and inches
                $height_m = $row['height'];
                $height_cm = $height_m * 100;  // Convert meters to centimeters
                $height_ft = floor($height_cm / 30.48);  // Calculate feet
                $height_in = round(($height_cm / 2.54) % 12);  // Calculate remaining inches
            ?>
            <tr>
                <td><?= htmlspecialchars($row['ohip']) ?></td>
                <td><?= htmlspecialchars($row['firstname']) ?></td>
                <td><?= htmlspecialchars($row['lastname']) ?></td>
                <td><?= htmlspecialchars($row['birthdate']) ?></td>
                <td><?= htmlspecialchars($row['doctor_firstname']) . ' ' . htmlspecialchars($row['doctor_lastname']) ?></td>
                <td><?= htmlspecialchars($row['weight']) ?> kg</td>
                <td><?= number_format($weight_lbs, 2) ?> lbs</td>
                <td><?= htmlspecialchars($row['height']) ?> m</td>
                <td><?= $height_ft ?> ft <?= $height_in ?> in</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

<?php mysqli_close($connection); ?>
</body>
</html>