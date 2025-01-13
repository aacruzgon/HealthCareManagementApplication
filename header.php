<!-- Programmer Name: 02 -->

<!-- 
File Purpose:
This HTML snippet represents the header section for the UWO HealthCare web application. It includes:
- A title to display the name of the healthcare system.
- Navigation buttons for different functionalities of the application, such as listing, adding, editing, and deleting patients, as well as viewing doctors and nurses.
  
Detailed Code Notes:
- Button Navigation: Each button uses a form submission with a `GET` request to navigate to the respective page of the application.
- Action Handling: The `page` parameter in the form submission directs the application to load the appropriate content dynamically.
-->

<div class="header">
    <h1>UWO HealthCare</h1>
    <div class="button-container">
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="mainmenu">Home</button>
        </form>
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="listPatient">List Patients</button>
        </form>
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="addPatient">Insert New Patient</button>
        </form>
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="deletePatient">Delete a Patient</button>
        </form>
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="editPatient">Edit a Patient</button>
        </form>
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="doctorOverview">List Doctors</button>
        </form>
        <form action="mainmenu.php" method="get">
            <button type="submit" name="page" value="nurseOverview">Nurse Overview</button>
        </form>
    </div>
</div>