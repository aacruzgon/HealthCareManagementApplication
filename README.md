# Clinic Management Database System  

## 📖 Overview  
This project involves designing and implementing a relational database to manage a clinic's staff (doctors and nurses) and patients. The system is optimized to handle complex relationships between doctors, nurses, and patients, while ensuring data consistency and efficiency.  

---
## ER-Diagram
![ER Diagram](https://github.com/aacruzgon/HealthCareManagementApplication/raw/main/images/ERDiagram.png)
--
## ✨ Features  

### **Doctors**  
- Unique **Doctor License ID**: 5-character alphanumeric (e.g., `ONT89`).  
- **Details Stored**:
  - First Name (up to 20 characters).  
  - Last Name (up to 20 characters).  
  - Date of Birth.  
  - Start Date (date of employment at the clinic).  

---

### **Nurses**  
- Unique **Nurse ID**: 5-character alphanumeric.  
- **Details Stored**:
  - First Name (up to 20 characters).  
  - Last Name (up to 20 characters).  
  - Start Date (date of employment at the clinic).  
- **Work Assignments**:  
  - Many-to-Many relationship with doctors.  
  - Tracks the **number of hours** worked per doctor.  
  - Each nurse must work for at least one doctor, but some doctors may have no assigned nurses.  
- **Reporting Structure**:  
  - Nurses report to one supervisor nurse (except the head nurse).  
  - A supervisor nurse may manage multiple nurses.  

---

### **Patients**  
- Unique **OHIP Number**: 9-character alphanumeric.  
- **Details Stored**:
  - First Name (up to 20 characters).  
  - Last Name (up to 20 characters).  
  - Date of Birth.  
  - Height (meters, up to 2 decimal places).  
  - Weight (kilograms, whole number).  
- **Doctor Assignment**:  
  - One-to-Many relationship where each patient is assigned to one doctor.  
  - Not all doctors are required to have patients.  

---

## 🔗 Relationships  

1. **Doctor-Nurse Relationship**:  
   - **Type**: Many-to-Many.  
   - Nurses can work for multiple doctors.  
   - Doctors can have multiple nurses assigned.  
   - Nurses must work for at least one doctor.  

2. **Nurse Reporting Hierarchy**:  
   - **Type**: Hierarchical.  
   - Nurses report to one supervisor nurse (except the head nurse).  
   - Supervisors can manage multiple nurses.  

3. **Doctor-Patient Relationship**:  
   - **Type**: One-to-Many.  
   - Each patient is treated by one doctor.  
   - Doctors may have no patients assigned.  

---

## ⚙️ Constraints  

- Unique identifiers for:
  - Doctors (`Doctor License ID`).  
  - Nurses (`Nurse ID`).  
  - Patients (`OHIP Number`).  
- Field validation:
  - Names limited to 20 characters.  
  - Height stored as meters (2 decimal places).  
  - Weight stored as an integer.  
- Relational integrity:
  - Every patient must have an assigned doctor.  
  - Nurses must be assigned to at least one doctor.  
  - All nurses (except the head nurse) must report to a supervisor nurse.  

---

## 🚀 Getting Started  

1. **Clone the repository**:  
   ```bash
   git clone https://github.com/aacruzgon/HealthCareManagementApplication
2.	Set up the database:
Run the provided SQL scripts to create and initialize the database schema:
	•	Create tables for doctors, nurses, and patients.
	•	Define relationships and constraints as outlined in the requirements.
3.	Interact with the database:
	•	Use the included queries or API endpoints (if applicable) to manage and retrieve clinic data.
	•	Test scenarios to verify database integrity and relational functionality.
4.	Customize and extend:
Modify the schema or queries to fit additional requirements or specific use cases for your clinic.
