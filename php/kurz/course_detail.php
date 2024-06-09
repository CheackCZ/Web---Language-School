<?php
    // Include connection file
    include ("../../inc/connection.php");
    
    // init session
    session_start();

    $course_id = $_GET['course_id'];

    // Fetch course details
    $sql_course = "SELECT *, (SELECT COUNT(*) FROM Zapis WHERE Zapis.kurz_id = Kurz.id) AS enrolled_students FROM Kurz WHERE id = ?";
    $stmt = $conn->prepare($sql_course);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result_course = $stmt->get_result();
    $course = $result_course->fetch_assoc();

    // Fetch students enrolled in the course
    $sql_students = "SELECT Student.jmeno, Student.prijmeni FROM Student 
                    JOIN Zapis ON Student.id = Zapis.student_id 
                    WHERE Zapis.kurz_id = ?";
    $stmt_students = $conn->prepare($sql_students);
    $stmt_students->bind_param("i", $course_id);
    $stmt_students->execute();
    $result_students = $stmt_students->get_result();
?>

<!DOCTYPE html>
    <html lang="cz">

        <!-- HEAD -->
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
            <!-- Title -->
            <title>Course Detail</title>
        
            <!-- Favicon -->
            <link rel="icon" type="image/svg+xml" href="../../img/book.svg">

            <!-- Bootstrap Links -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        </head>

        <!-- BODY -->
        <body class="bg-light">

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
                                    <a class="nav-link text-white" href="user_dash.php">(User) Dashboard</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                
                </nav>
            </header>

            <!-- Main -->
            <main class="bg-light">

                <!-- Course Detail Title -->
                <div class="text-center pt-5">
                    <h1>Course Detail</h1>
                </div>    
                
                <div class="container mt-5">

                    <!-- Div with course details -->
                    <div class="card border-0 shadow rounded">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlspecialchars($course['nazev']); ?></h2>
                            <p class="card-text">Capacity: <?php echo htmlspecialchars($course['kapacita']); ?></p>
                            <p class="card-text">Enrolled Students: <?php echo htmlspecialchars($course['enrolled_students']); ?></p>
                            <p class="card-text">Start Date: <?php echo htmlspecialchars($course['datum_zacatku']); ?></p>
                            <p class="card-text">End Date: <?php echo htmlspecialchars($course['datum_konce']); ?></p>
                        </div>
                    </div>

                    <!-- Enrolled Students Subtitle -->
                    <h3 class="mt-4">Students Enrolled</h3>

                    <!-- List of enrolled students -->
                    <ul class="list-group mb-5">
                        <?php
                            if ($result_students->num_rows > 0) {
                                while($row = $result_students->fetch_assoc()) {
                                    echo '<li class="list-group-item">' . htmlspecialchars($row['jmeno']) . ' ' . htmlspecialchars($row['prijmeni']) . '</li>';
                                }
                            } else {
                                echo '<li class="list-group-item">No students enrolled in this course.</li>';
                            }
                        ?>
                    </ul>
                </div>
                
                <!-- Button to get back to User dash -->
                <div class="container d-flex justify-content-center align-items-center full-height">
                    <a href="../dashes/user_dash.php" class="btn btn-primary mb-5 px-3">Back</a>
                </div>
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
                                        <a href="tel:+420123465879" class="text-primary">+420123465879</a>
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