<!-- Programmer Name: 02 -->

<!-- 
File Purpose:
This HTML template serves as the main entry point for the UWO Healthcare web application. It includes:
- A reusable header for navigation across the application.
- A dynamic content section that loads specific PHP files based on the "page" GET parameter.

Detailed Code Notes:
- Dynamic Content Loading:
  - The "page" parameter determines which file to include dynamically.
  - Ensures modularity by loading only the required file for the selected functionality.
- Default Behavior:
  - If no "page" parameter is specified, a welcome message is displayed.
- Reusable Header:
  - The `header.php` file is included to ensure consistency in navigation across all pages.
 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>UWO HealthCare</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Include the reusable header -->
    <?php include 'header.php'; ?>

    <!-- Content section -->
    <div class="content">
        <?php
        // Determine which page to load based on the "page" GET parameter
        $page = $_GET['page'] ?? 'home';

        // Load the appropriate content file based on the selected page
        switch ($page) {
            case 'listPatient':
                include 'listPatient.php';
                break;
            case 'addPatient':
                include 'addPatient.php';
                break;
            case 'deletePatient':
                include 'deletePatient.php';
                break;
            case 'editPatient':
                include 'editPatient.php';
                break;
            case 'doctorOverview':
                include 'doctorOverview.php';
                break;
            case 'listDoctorsAndPatients':
                include 'listDoctorsAndPatients.php';
                break;
            case 'nurseOverview':
                include 'nurseOverview.php';
                break;
            default:
                echo "<p>Welcome to the UWO Healthcare Management System. Please select an option above.</p>";
        }
        ?>
    </div>

</body>
</html>