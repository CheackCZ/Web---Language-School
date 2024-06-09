<?php
    // Connection to database
    include("../../inc/connection.php");
   
    // Init session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['uzivjm'])) {
        header("Location: /login/login.php");
        exit;
    }

    // Get the username from session
    $uzivjm = $_SESSION['uzivjm'];

    // Fetch the student_id using the username
    $sql_student = "SELECT id FROM Student WHERE uzivjm = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("s", $uzivjm);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();

    if ($result_student->num_rows > 0) {
        $student = $result_student->fetch_assoc();
        $student_id = $student['id'];
    } else {
        echo "Error: Student not found.";
        exit;
    }

    // Handle the enrollment form submission
    if (isset($_POST['enroll'])) {
        $course_id_to_enroll = $_POST['course_id'];
        $datum_zapisu = date("Y-m-d"); // Capture the current date

        $sql_enroll = "INSERT INTO Zapis (student_id, kurz_id, datum_zapisu) VALUES (?, ?, ?)";
        $stmt_enroll = $conn->prepare($sql_enroll);
        $stmt_enroll->bind_param("iis", $student_id, $course_id_to_enroll, $datum_zapisu);
        $stmt_enroll->execute();

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Handle the unenrollment form submission
    if (isset($_POST['unenroll'])) {
        $course_id_to_unenroll = $_POST['course_id'];

        $sql_unenroll = "DELETE FROM Zapis WHERE student_id = ? AND kurz_id = ?";
        $stmt_unenroll = $conn->prepare($sql_unenroll);
        $stmt_unenroll->bind_param("ii", $student_id, $course_id_to_unenroll);
        $stmt_unenroll->execute();

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // User's Courses select
    $sql_my_courses = "SELECT Kurz.*, COUNT(Zapis.student_id) AS enrolled_students 
                       FROM Kurz 
                       JOIN Zapis ON Kurz.id = Zapis.kurz_id 
                       WHERE Zapis.student_id = ?
                       GROUP BY Kurz.id";
    $stmt = $conn->prepare($sql_my_courses);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result_my_courses = $stmt->get_result();

    // All Courses select with ordering
    $sql_all_courses = "
        SELECT 
            Kurz.*, 
            (SELECT COUNT(*) FROM Zapis WHERE Zapis.kurz_id = Kurz.id) AS enrolled_students,
            (SELECT COUNT(*) FROM Zapis WHERE Zapis.kurz_id = Kurz.id AND Zapis.student_id = ?) AS is_enrolled,
            CASE 
                WHEN (SELECT COUNT(*) FROM Zapis WHERE Zapis.kurz_id = Kurz.id) >= Kurz.kapacita THEN 1 
                ELSE 0 
            END AS is_full
        FROM Kurz
        ORDER BY 
            is_full ASC, 
            is_enrolled ASC,
            is_full, 
            Kurz.nazev ASC
    ";
    $stmt_all_courses = $conn->prepare($sql_all_courses);
    $stmt_all_courses->bind_param("i", $student_id);
    $stmt_all_courses->execute();
    $result_all_courses = $stmt_all_courses->get_result();
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

                <!-- User Dash Title -->
                <div class="text-center pt-5">
                    <h1>User Dashboard</h1>
                </div>
                
                <!-- Div with Courses -->
                <div class="container mt-5">

                    <!-- Subtitle for User's Courses -->
                    <h2 class="mb-3">My Courses</h2>
                    
                    <!-- Div with cards of Course where User is enrolled -->
                    <div class="row" id="myCoursesContainer">
                        <?php
                            if ($result_my_courses->num_rows > 0) {
                                while($row = $result_my_courses->fetch_assoc()) {
                                    echo '<div class="col-md-4 mb-4">';
                                    echo '<div class="card h-100">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($row["nazev"]) . '</h5>';
                                    echo '<p class="card-text">Capacity: ' . htmlspecialchars($row["kapacita"]) . '<br>Enrolled: ' . htmlspecialchars($row["enrolled_students"]) . '<br>Start Date: ' . htmlspecialchars($row["datum_zacatku"]) . '<br>End Date: ' . htmlspecialchars($row["datum_konce"]) . '</p>';
                                    echo '<a href="../kurz/course_detail.php?course_id=' . htmlspecialchars($row["id"]) . '" class="btn btn-primary w-100">View Course</a>';
                                    echo '<form action="" method="POST" class="mt-2">';
                                    echo '<input type="hidden" name="course_id" value="' . htmlspecialchars($row["id"]) . '">';
                                    echo '<button type="submit" name="unenroll" class="btn btn-danger w-100">Unenroll</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>You are not enrolled in any courses.</p>';
                            }
                        ?>
                    </div>

                    <!-- Subtitle for All Courses -->
                    <h2 class="mb-3 mt-3">All Courses</h2>

                    <!-- Div with cards of All Courses -->
                    <div class="row mb-5" id="allCoursesContainer">
                        <?php
                            if ($result_all_courses->num_rows > 0) {
                                while($row = $result_all_courses->fetch_assoc()) {
                                    $course_id = $row["id"];

                                    echo '<div class="col-md-4 mb-4">';
                                    echo '<div class="card h-100">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($row["nazev"]) . '</h5>';
                                    echo '<p class="card-text">Capacity: ' . htmlspecialchars($row["kapacita"]) . '<br>Enrolled: ' . htmlspecialchars($row["enrolled_students"]) . '<br>Start Date: ' . htmlspecialchars($row["datum_zacatku"]) . '<br>End Date: ' . htmlspecialchars($row["datum_konce"]) . '</p>';
                                    echo '<a href="../kurz/course_detail.php?course_id=' . htmlspecialchars($row["id"]) . '" class="btn btn-primary w-100 bg-white text-primary">View Course</a>';

                                    if ($row["enrolled_students"] == 0) {
                                        echo '<input type="text" readonly class="form-control text-center text-success mt-2 border-success bg-white" placeholder="EMPTY">';
                                    }

                                    $is_enrolled = $row["is_enrolled"];
                                    if ($is_enrolled == 0) {
                                        if ($row["enrolled_students"] < $row["kapacita"]) {
                                            echo '<form action="" method="POST" class="mt-2">';
                                            echo '<input type="hidden" name="course_id" value="' . htmlspecialchars($row["id"]) . '">';
                                            echo '<button type="submit" name="enroll" class="btn btn-primary w-100">Enroll</button>';
                                            echo '</form>';
                                        } else {
                                            echo '<input type="text" class="form-control text-center mt-2 border-danger text-danger bg-white" value="Course is full" readonly>';
                                        }
                                    } else {
                                        if ($row["enrolled_students"] >= $row["kapacita"]) {
                                            echo '<input type="text" class="form-control text-center mt-2 border-danger text-danger bg-white" value="Course is full" readonly>';
                                        }
                                    }

                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No courses available.</p>';
                            }
                        ?>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-body-tertiary text-center bg-dark">
                
                <div class="text-center text-muted pt-4">
                    <h3>Contact us on:</h3>
                </div>
                
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