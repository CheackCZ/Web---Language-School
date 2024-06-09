<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    
    // Connection to database
    require ("../../inc/connection.php");

    // init session
    session_start();

    // Check if admin
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("Location: ./login.php");
        exit();
    }

    // Select evrything from Kurz 
    $sql = 'select * from kurz';
    $result = $conn->query($sql);

    // Select evrything from Student 
    $sqlStud = 'select * from student';
    $resultStud = $conn->query($sqlStud);

    // Select Student, Kurz and Zapis data 
    $sqlEnt = 'select z.id, jmeno, prijmeni, nazev, datum_zapisu from zapis z
                inner join student s on s.id=z.student_id
                inner join kurz k on k.id=z.kurz_id
                order by datum_zapisu desc;';
    $resultEnt = $conn->query($sqlEnt);
?>

<!DOCTYPE html>

    <html lang="cz">
        <!-- HEAD -->
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
            <!-- Title -->
            <title>Jazyková Škola</title>

            <!-- Favicon -->
            <link rel="icon" type="image/svg+xml" href="../../img/book.svg">
    
            <!-- Bootstrap Links -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        </head>
        
        <!-- BODY -->
        <body>
            <!-- Header -->
            <header>
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    
                    <div class="container">
                        
                        <!-- Logo -->
                        <a class="navbar-brand d-flex align-items-center" href="../app.php">
                            <img src="../../img/book.svg" height="24" alt="JK Logo" loading="lazy" style="margin-top: -1px;" />
                            <p class="text-white ps-2 mb-0">Language School</p>
                        </a>
                
                        <!-- Hamburger Menu --> 
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                
                        <!-- Navbar Links -->
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="../login/logout.php">Logout</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="admin_dash.php">(Admin) Dashboard</a>
                                </li>
                            </ul>
                        </div>
                    
                    </div>
                
                </nav>
            </header>

            <!-- Main -->
            <main class="bg-light">
                
                <!-- Section 1 (Courses) -->
                <section class="section">
                   
                    <!-- Admin Page Title -->
                    <h1 class="text-center pt-5">Admin Dashboard</h1>

                    <!-- Subtitle for Courses part-->
                    <h2 class="text-center pt-3">Courses</h2>
                    
                    <!-- Button to add Course -->
                    <div class="d-flex justify-content-center mb-3">
                        <a href="../kurz/add_course.php" class="btn btn-primary my-2">Add Course</a>
                    </div>
                    
                    <!-- Table with Courses and their Details + buttons to manage them -->
                    <div class="d-flex justify-content-center">
                        <div class="table-responsive" style="width: auto;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Capacity</th>
                                        <th>Start Date</th>
                                        <th>Konec End Date</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["nazev"] . "</td>";
                                            echo "<td>" . $row["kapacita"] . "</td>";
                                            echo "<td>" . $row["datum_zacatku"] . "</td>";
                                            echo "<td>" . $row["datum_konce"] . "</td>";
                                            echo '<td><form action="../kurz/update_course.php" method="POST"><input type="hidden" name="id" value="' . $row["id"] . '"><button type="submit" class="bg-warning text-white rounded-3 border-0">Edit</button></form></td>';
                                            echo '<td><form action="../kurz/course_delete.php" method="POST"><input type="hidden" name="id" value="' . $row["id"] . '"><button type="submit" class="bg-danger text-white rounded-3 border-0">Delete</button></form></td>';
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </section>

                <!-- Section 2 (Students) -->
                <section class="section">
                    
                    <!-- Subtitle for Students part-->
                    <h2 class="text-center pt-4">Students</h2>
                    
                    <!-- Button to add Student -->
                    <div class="d-flex justify-content-center mb-3">
                        <a href="../student/add_student.php" class="btn btn-primary my-2">Add Student</a>
                    </div>

                    <!-- Table with Students and their Details + buttons to manage them -->
                    <div class="d-flex justify-content-center">
                        <div class="table-responsive" style="width: auto;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Last Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Admin</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while ($row = $resultStud->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["jmeno"] . "</td>";
                                            echo "<td>" . $row["prijmeni"] . "</td>";
                                            echo "<td>" . $row["uzivjm"] . "</td>";
                                            echo "<td>" . $row["email"] . "</td>";
                                            echo "<td>" . $row["heslo"] . "</td>";
                                            echo "<td>" ; if ($row["admin"]==0) {echo "false";} else { echo "true";} echo "</td>";
                                            echo '<td><form action="../student/update_student.php" method="POST"><input type="hidden" name="student_id" value="' . $row["id"] . '"><button type="submit" class="bg-warning text-white rounded-3 border-0">Edit</button></form></td>';
                                            echo '<td><form action="../student/stud_delete.php" method="POST"><input type="hidden" name="id" value="' . $row["id"] . '"><button type="submit" class="bg-danger text-white rounded-3 border-0">Delete</button></form></td>';
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </section>

                <!-- Section 3 (Entries) -->
                <section class="section">
                    
                    <!-- Subtitle for Entries part-->
                    <h2 class="text-center pt-4 mb-3">Entries</h2>

                    <!-- Table with Entries and their Details-->
                    <div class="d-flex justify-content-center">
                        <div class="table-responsive" style="width: auto;">
                            <table class="table table-bordered table-striped mb-5">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Last Name</th>
                                        <th>Course</th>
                                        <th>Enrollment Date</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while ($row = $resultEnt->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["jmeno"] . "</td>";
                                            echo "<td>" . $row["prijmeni"] . "</td>";
                                            echo "<td>" . $row["nazev"] . "</td>";
                                            echo "<td>" . $row["datum_zapisu"] . "</td>";
                                        echo '<td><form action="../zapis/update_zapis.php" method="POST"><input type="hidden" name="zapis_id" value="' . $row["id"] . '"><button type="submit" class="bg-warning text-white rounded-3 border-0">Edit</button></form></td>';                                            
                                            echo '<td><form action="../zapis/zapis_delete.php" method="POST"><input type="hidden" name="id" value="' . $row["id"] . '"><button type="submit" class="bg-danger text-white rounded-3 border-0">Delete</button></form></td>';
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="bg-body-tertiary text-center bg-dark">
                
                <!-- Footer title -->
                <div class="text-center text-muted pt-4">
                    <h3>Contact us on:</h3>
                </div>

                <!-- Footer contacts -->
                <div class="container p-4 pb-0">
                    <section class="">
                        <form action="">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-3 col-12">
                                    <p class="text-muted">
                                        Email:
                                        <a href="mailto:info@ls.com" class="text-primary">info@ls.com</a>
                                    </p>
                                </div>

                                <div class="col-md-3 col-12">
                                    <p class="text-muted">
                                        Phone:
                                        <a href="mailto:info@ls.com" class="text-primary">+420123465879</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>

                <!-- Footer Copyright -->
                <div class="text-center p-3 text-muted" style="background-color: rgba(0, 0, 0, 0.05);">
                    © 2024 Copyright
                    <a class="text-body text-muted" href="ondrejfaltin.cz">ondrejfaltin.cz</a>
                </div>
            </footer>

            <!-- JS Links -->
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
    </html>