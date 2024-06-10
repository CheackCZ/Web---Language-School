# Language School Web Application
## Project Description
This project is a web application designed for a language school. It allows users to manage and track student enrollments in courses. The application is developed using PHP and stores data in a MySQL database. The project includes features for both administrators and regular users, with different levels of access and capabilities.

## Features
### General
- Responsive design using Bootstrap.
- Standard layout with a navigation menu, main content area, and footer.
- User authentication system with roles for administrators and regular users.

### Administrator
- Login as an administrator.
- CRUD operations on all database tables through forms.
- View and edit data in all tables used as reference data.

### User
- Login and registration as a regular user.
- Enroll in courses through a form with dropdown lists populated from other tables.
- View and edit enrollment data.

## Data Management
- Student: Manage student information including name, username, email, password, and admin status.
- Course: Manage course details including name, capacity, start date, and end date.
- Enrollment: Track student enrollments in courses.

## Installation
### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (e.g., Apache, Nginx)

### Steps
1. Clone the repository:
```
git clone https://github.com/your-username/language-school-app.git
cd language-school-app
```

2. Set up the database:
- Create a new MySQL database.
- Import the sql/query.sql file to create tables and insert initial data.
```
mysql -u your-username -p your-database-name < database.sql
```

3. Configure the database connection:
- Update the database connection settings in inc/connection.php with your database credentials.

4. Start the web server:
- If using Apache, place the project folder in the htdocs directory.
- If using another web server, configure it to serve the project directory.

5. Access the application:
- Open a web browser and navigate to [http://localhost/name-of-project].

## Usage
### User Roles and Functionalities
__Administrator__
1. Login:
- Navigate to the login page log in as an administrator.

2. Manage Data:
- Use forms to insert, update, or delete data in the Student, Course, and Enrollment tables.
- View all data in a tabular format with options to edit or delete each entry.

__Regular User__
1. Login/Registration:
- Navigate to the login page and register or log in as a regular user.

2. Enroll in Courses:
- Use forms to enroll in courses. Select from available courses using dropdown lists.

3. View Enrollments:
- View a list of courses the user is enrolled in.
- Unenroll from courses if needed.

## Database Schema
### Tables
__Student__
```
id: INT, Primary Key, Auto Increment
jmeno: VARCHAR(20)
prijmeni: VARCHAR(20)
uzivjm: VARCHAR(32), Unique
email: VARCHAR(50), Unique
heslo: VARCHAR(64)
admin: BIT, Default False
```

__Course__
```
id: INT, Primary Key, Auto Increment
nazev: VARCHAR(50)
kapacita: INT
datum_zacatku: DATE
datum_konce: DATE
```

__Enrollment__
```
id: INT, Primary Key, Auto Increment
student_id: INT, Foreign Key references Student(id)
kurz_id: INT, Foreign Key references Course(id)
datum_zapisu: DATE
```
