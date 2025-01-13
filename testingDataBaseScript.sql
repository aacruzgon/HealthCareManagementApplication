-- SELECTING DATABASE
USE assign2db;

-- Part 1 SQL Updates

SELECT * FROM nurse;

UPDATE nurse SET lastname = 'Cyrus' WHERE firstname = 'Miley';

UPDATE nurse n, workingfor w, doctor d SET n.startdate = d.startdate WHERE n.nurseid = w.nurseid AND w.docid = d.docid AND d.lastname = 'Tanaka';

SELECT * FROM nurse;

-- Part 2 SQL Inserts

-- Inserting my favorite TV character into the doctor table
INSERT INTO doctor (docid, firstname, lastname, birthdate, startdate) VALUES ('GRH12', 'Itachi', 'Uchiha', '1959-06-11', '2004-09-01');

-- Inserting another TV character from the same show into the patient table
INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES ('OHIP12345', 'Sasuke', 'Uchiha', 75, '1970-11-28', 1.80, 'GRH12');

-- Inserting my favorite TV actor into the nurse table
INSERT INTO nurse (nurseid, firstname, lastname, startdate, reporttonurseid) VALUES ('EPO12', 'Young', 'Sheldon', '2010-01-15', 'BBBB2');

-- Assigning the nurse to work 50 hours for the doctor
INSERT INTO workingfor (docid, nurseid, hours) VALUES ('GRH12', 'EPO12', 50);

-- Proving that all the data above was added
SELECT * FROM doctor WHERE docid = 'GRH12';
SELECT * FROM patient WHERE ohip = 'OHIP12345';
SELECT * FROM nurse WHERE nurseid = 'EPO12';
SELECT * FROM workingfor WHERE docid = 'GRH12' AND nurseid = 'EPO12';

-- Part 3 SQL Queries

-- Query 1
SELECT lastname FROM patient;

-- Query 2
SELECT DISTINCT lastname FROM patient;

-- Query 3
SELECT * FROM doctor ORDER BY startdate;

-- Query 4
SELECT ohip, firstname, lastname, weight FROM patient WHERE weight >= 50 ORDER BY weight;

-- Query 5
SELECT p.firstname, p.lastname FROM patient p JOIN doctor d ON p.treatsdocid = d.docid WHERE d.lastname = 'Tanaka';

-- Query 6
SELECT d.firstname AS 'Doctor First Name', d.lastname AS 'Doctor Last Name', p.firstname AS 'Patient First Name', p.lastname AS 'Patient Last Name' FROM doctor d LEFT JOIN patient p ON p.treatsdocid = d.docid;

-- Query 7
SELECT d.firstname, d.lastname FROM doctor d LEFT JOIN patient p ON p.treatsdocid = d.docid WHERE p.ohip IS NULL;

-- Query 8
SELECT AVG(hours) AS 'Average Hours' FROM workingfor;

-- Query 9
SELECT n1.firstname AS 'Nurse First Name', n1.lastname AS 'Nurse Last Name', n2.firstname AS 'Supervisor First Name', n2.lastname AS 'Supervisor Last Name' FROM nurse n1 JOIN nurse n2 ON n1.reporttonurseid = n2.nurseid;

-- Query 10
SELECT n.firstname AS 'First Name', n.lastname AS 'Last Name', SUM(w.hours) AS 'Total Hours', CONCAT('$', FORMAT(SUM(w.hours) * 30, 2)) AS 'Total Pay' FROM nurse n JOIN workingfor w ON n.nurseid = w.nurseid GROUP BY n.nurseid ORDER BY SUM(w.hours) * 30 DESC;

-- Query 11
SELECT p.firstname AS 'Patient First Name', p.lastname AS 'Patient Last Name', n.firstname AS 'Nurse First Name', n.lastname AS 'Nurse Last Name' FROM patient p JOIN workingfor w ON p.treatsdocid = w.docid JOIN nurse n ON w.nurseid = n.nurseid;

-- Query 12
SELECT p.firstname AS 'Patient First Name', p.lastname AS 'Patient Last Name', TIMESTAMPDIFF(YEAR, p.birthdate, CURDATE()) AS 'Patient Age', p.birthdate AS 'Patient Birthdate', d.firstname AS 'Doctor First Name', d.lastname AS 'Doctor Last Name', TIMESTAMPDIFF(YEAR, d.birthdate, CURDATE()) AS 'Doctor Age', d.birthdate AS 'Doctor Birthdate' FROM patient p JOIN doctor d ON p.treatsdocid = d.docid WHERE TIMESTAMPDIFF(YEAR, d.birthdate, CURDATE()) < TIMESTAMPDIFF(YEAR, p.birthdate, CURDATE());

-- Query 13
SELECT DISTINCT n.firstname, n.lastname
FROM nurse n
WHERE n.nurseid NOT IN (
    SELECT w.nurseid
    FROM workingfor w
    JOIN doctor d ON w.docid = d.docid
    WHERE d.lastname = 'Tanaka'
);

-- Query 14
SELECT n.firstname, n.lastname, COUNT(DISTINCT w.docid) AS 'Number of Doctors'
FROM nurse n
JOIN workingfor w ON n.nurseid = w.nurseid
GROUP BY n.nurseid
HAVING COUNT(DISTINCT w.docid) > 1;

-- Query 15 - Find the doctors who have more than two patients and list their names and the number of patients they have
SELECT d.firstname AS 'Doctor First Name', d.lastname AS 'Doctor Last Name',
       COUNT(p.ohip) AS 'Number of Patients'
FROM doctor d
JOIN patient p ON d.docid = p.treatsdocid
GROUP BY d.docid
HAVING COUNT(p.ohip) > 2;

-- Part 4 SQL Views/Deletes

-- Create a view to show the first and last name of each doctor and how many patients they are treating
CREATE VIEW doctor_patient_counts AS
SELECT d.firstname, d.lastname, COUNT(p.ohip) AS numofpat
FROM doctor d
JOIN patient p ON d.docid = p.treatsdocid
GROUP BY d.docid
HAVING COUNT(p.ohip) > 0;

-- Prove that it works by selecting all the columns from your view but only if a doctor has exactly 2 patients
SELECT * FROM doctor_patient_counts
WHERE numofpat = 2;

-- Write a query to show all the doctor table information
SELECT * FROM doctor;

-- Delete the doctor with the doctor id of HIT45
DELETE FROM doctor
WHERE docid = 'HIT45';

-- Prove that the doctor was deleted
SELECT * FROM doctor
WHERE docid = 'HIT45';

-- Write a query to count the number of doctors in the doctor table
SELECT COUNT(*) AS 'Number of Doctors' FROM doctor;

-- Delete the doctor with the doctorid of RAD34
DELETE FROM doctor
WHERE docid = 'RAD34';

-- Show the number of doctors again to see if it worked
SELECT COUNT(*) AS 'Number of Doctors' FROM doctor;

-- doctor with docid HIT45 is succesfully deleted because there is no foreign key constrain preventing it from deletion. 
-- doctor with docid RAD34 because it has a foreign key constraint.
