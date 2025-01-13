<!-- 
Programmer Name: 02

File Purpose:
This PHP script provides an overview of a selected nurse, displaying:
- Nurse details (name and supervisor, if applicable).
- Doctors the nurse has worked for and the hours spent with each doctor.
- Total hours worked by the nurse.

Detailed Code Notes:
- Nurse Selection:
  - Populates a dropdown with all nurses ordered alphabetically by last name.
  - Dynamically fetches and displays details based on the selected nurse.
- Data Queries:
  - Retrieves nurse details, associated doctors, and hours worked.
  - Includes a query to fetch the supervisor's name if the nurse has a supervisor.
- Total Hours Calculation: Sums up the hours worked with all associated doctors.
- Dynamic Table: Displays a table of doctors and hours worked, along with total hours and supervisor details.
-->


<?php
// Include database connection
include 'connectdb.php';

$nurseid = '';
$nurseData = [];
$doctorsData = [];
$totalHours = 0;
$supervisorData = [];

// Fetch list of nurses for the dropdown
$nurseListQuery = "SELECT nurseid, firstname, lastname FROM nurse ORDER BY lastname ASC";
$nurseListResult = mysqli_query($connection, $nurseListQuery);

// Handle form submission to get the selected nurse's details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nurseid'])) {
    $nurseid = $_POST['nurseid'];

    // Query to get selected nurse's details
    $nurseQuery = "
        SELECT firstname, lastname, reporttonurseid 
        FROM nurse 
        WHERE nurseid = '$nurseid'";
    $nurseResult = mysqli_query($connection, $nurseQuery);
    $nurseData = mysqli_fetch_assoc($nurseResult);

    // Query to get doctors and hours worked by the selected nurse
    $doctorHoursQuery = "
        SELECT doctor.firstname AS doctor_firstname, 
               doctor.lastname AS doctor_lastname, 
               workingfor.hours 
        FROM workingfor 
        JOIN doctor ON workingfor.docid = doctor.docid 
        WHERE workingfor.nurseid = '$nurseid'";
    $doctorHoursResult = mysqli_query($connection, $doctorHoursQuery);

    // Process doctors data and calculate total hours
    while ($row = mysqli_fetch_assoc($doctorHoursResult)) {
        $doctorsData[] = $row;
        $totalHours += $row['hours'];
    }

    // Query to get supervisor's name if there's a supervisor
    if (!empty($nurseData['reporttonurseid'])) {
        $supervisorQuery = "
            SELECT firstname, lastname 
            FROM nurse 
            WHERE nurseid = '{$nurseData['reporttonurseid']}'";
        $supervisorResult = mysqli_query($connection, $supervisorQuery);
        $supervisorData = mysqli_fetch_assoc($supervisorResult);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Nurse Overview</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<h1>Nurse Overview</h1>

<!-- Nurse Selection Form -->
<form method="post" class="sort-form">
    <div class="sort-title">Select Nurse:</div>
    <div class="dropdown-container">
        <select name="nurseid" id="nurseid" onchange="this.form.submit()">
            <option value="">Select a nurse</option>
            <?php while ($nurse = mysqli_fetch_assoc($nurseListResult)) { ?>
                <option value="<?= $nurse['nurseid'] ?>" <?= $nurseid == $nurse['nurseid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($nurse['lastname'] . ', ' . $nurse['firstname']) ?>
                </option>
            <?php } ?>
        </select>
    </div>
</form>

<?php if ($nurseData): ?>
    <h2>Nurse Details</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($nurseData['firstname'] . ' ' . $nurseData['lastname']) ?></p>

    <h2>Doctors Worked For</h2>
    <table>
        <thead>
            <tr>
                <th>Doctor First Name</th>
                <th>Doctor Last Name</th>
                <th>Hours Worked</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctorsData as $doctor) { ?>
                <tr>
                    <td><?= htmlspecialchars($doctor['doctor_firstname']) ?></td>
                    <td><?= htmlspecialchars($doctor['doctor_lastname']) ?></td>
                    <td><?= htmlspecialchars($doctor['hours']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php if ($supervisorData): ?>
        <h2>Supervisor</h2>
        <p><strong>Supervisor Name:</strong> <?= htmlspecialchars($supervisorData['firstname'] . ' ' . $supervisorData['lastname']) ?></p>
    <?php else: ?>
        <p><strong>Supervisor:</strong> None</p>
    <?php endif; ?>
<?php endif; ?>

<?php mysqli_close($connection); ?>
</body>
</html>