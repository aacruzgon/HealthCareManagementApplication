<!-- 
Programmer Name: 02

File Purpose:
This PHP script provides an overview of doctors, displaying either:
- Doctors without patients.
- Doctors with their assigned patients.

It allows sorting of results by first name or last name in ascending or descending order.

Detailed Code Notes:
- View Selection: Dynamically switches between "Doctors Without Patients" and "Doctors With Patients" views using SQL queries.
- Sorting: Ensures sorting options are safe for SQL execution.
- Dynamic Table: Generates table headers and rows dynamically based on the selected view.
- Query Optimization: Uses LEFT JOIN for "without patients" view and INNER JOIN for "with patients" view.
-->

<?php

// Include database connection
include 'connectdb.php';

// Initialize default values for sorting options and view selection
$sortField = 'lastname';
$order = 'ASC';
$view = 'without_patients'; // Default view to "Doctors Without Patients"

// Get sorting options and view from the form, if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sortField = $_POST['sortField'] ?? 'lastname';
    $order = $_POST['order'] ?? 'ASC';
    $view = $_POST['view'] ?? 'without_patients';

    // Ensure sortField and order are safe for use in SQL
    $sortField = ($sortField === 'firstname') ? 'firstname' : 'lastname';
    $order = ($order === 'DESC') ? 'DESC' : 'ASC';
}

// SQL queries based on selected view
if ($view === 'without_patients') {
    // Query for doctors without patients
    $query = "
        SELECT 
            doctor.docid,
            doctor.firstname,
            doctor.lastname
        FROM 
            doctor
        LEFT JOIN 
            patient ON doctor.docid = patient.treatsdocid
        WHERE 
            patient.ohip IS NULL
        ORDER BY 
            $sortField $order";
} else {
    // Query for doctors with patients
    $query = "
        SELECT 
            doctor.docid,
            doctor.firstname AS doctor_firstname,
            doctor.lastname AS doctor_lastname,
            patient.firstname AS patient_firstname,
            patient.lastname AS patient_lastname
        FROM 
            doctor
        INNER JOIN 
            patient ON doctor.docid = patient.treatsdocid
        ORDER BY 
            doctor.$sortField $order";
}

$result = mysqli_query($connection, $query);

// Check for query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Overview</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h1>Doctor Overview</h1>

<!-- View Selection Form -->
<form method="post" class="sort-form">
    <div class="sort-title">Select View:</div>
    <div class="dropdown-container">
        <select name="view" id="view" onchange="this.form.submit()">
            <option value="without_patients" <?= $view == 'without_patients' ? 'selected' : '' ?>>Doctors Without Patients</option>
            <option value="with_patients" <?= $view == 'with_patients' ? 'selected' : '' ?>>Doctors With Patients</option>
        </select>
    </div>
</form>

<!-- Sorting Form -->
<form method="post" class="sort-form">
    <input type="hidden" name="view" value="<?= $view ?>"> <!-- Keep the selected view -->
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

<!-- Table to display results based on selected view -->
<table>
    <thead>
        <tr>
            <?php if ($view === 'without_patients'): ?>
                <th>Doctor ID</th>
                <th>First Name</th>
                <th>Last Name</th>
            <?php else: ?>
                <th>Doctor ID</th>
                <th>Doctor First Name</th>
                <th>Doctor Last Name</th>
                <th>Patient First Name</th>
                <th>Patient Last Name</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <?php if ($view === 'without_patients'): ?>
                    <td><?= htmlspecialchars($row['docid']) ?></td>
                    <td><?= htmlspecialchars($row['firstname']) ?></td>
                    <td><?= htmlspecialchars($row['lastname']) ?></td>
                <?php else: ?>
                    <td><?= htmlspecialchars($row['docid']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_firstname']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_lastname']) ?></td>
                    <td><?= htmlspecialchars($row['patient_firstname']) ?></td>
                    <td><?= htmlspecialchars($row['patient_lastname']) ?></td>
                <?php endif; ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php mysqli_close($connection); ?>
</body>
</html>