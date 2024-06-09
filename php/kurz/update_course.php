<?php
    // init session
    session_start();
    
    // Include connection file
    include '../../inc/connection.php';

    // Check if the user is logged in and is an admin
    if (!$_SESSION['admin']) {
        header("Location: ../login/login.php");
        exit();
    }

    // Initialize an empty array to hold course details
    $kurz = [
        'nazev' => '',
        'kapacita' => '',
        'datum_zacatku' => '',
        'datum_konce' => '',
    ];

    // Check if the book_id is set in POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        error_log(print_r($_POST, true));

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['capacity']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
            
            // Update the course details
            $id = intval($_POST['id']);
            $name = $_POST['name'];
            $capacity = $_POST['capacity'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            $sql = "UPDATE kurz SET nazev = ?, kapacita = ?, datum_zacatku = ?, datum_konce = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sissi", $name, $capacity, $start_date, $end_date, $id);

            if ($stmt->execute()) {
                echo "Course updated successfully.";
                header("location:../dashes/admin_dash.php");
            } else {
                echo "Error updating course: " . $stmt->error;
            }
            $stmt->close();

        } elseif (isset($_POST['id'])) {
            
            // Fetch course details from the database
            $id = intval($_POST['id']);

            $sql = "SELECT * FROM kurz WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $kurz = $result->fetch_assoc();
            } else {
                echo "Course not found.";
                $conn->close();
                exit();
            }
            $stmt->close();

        } else {
            
            echo "Invalid request.";
            $conn->close();
            exit();
        
        }
    }
    $conn->close();
?>

<!-- Form for login -->
<!doctype html>
    <html>
        <!-- Head -->
        <head>
            <meta charset="utf-8">

            <!-- Title -->
            <title>Edit Course</title>

            <!-- Favicon -->
            <link rel="icon" type="image/svg+xml" href="../../img/book.svg">

            <!-- Bootstrap 5 import -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        
            <!-- Styles for background -->
            <style>
                .gradient-custom {
                    background: #6a11cb;

                    background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

                    background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
                }
            </style>
        </head>

        <!-- Body -->
        <body class="gradient-custom">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-dark">
                
                <div class="container">

                    <!-- Logo -->
                    <a class="navbar-brand d-flex align-items-center" href="../app.php">
                        <img src="../../img/book.svg" height="24" alt="JK Logo" loading="lazy" style="margin-top: -1px;" />
                        <p class="text-white ps-2 mb-0">Language School</p>
                    </a>
                
                    <!-- Navbar Links -->
                    <div class="d-flex align-items-center">
                        <button data-mdb-ripple-init type="button" class="btn btn-primary me-3" onclick="location.href='../admin_dash.php'">(Admin) Dashboard</button>
                    </div>
                
                </div>

            </nav>

            <!-- Main -->
            <main class="vh-80">
            
                <div class="container form-control center w-50 bg-dark pt-5 pb-3 px-3 mt-5 shadow border-0" style="border-radius: 1rem;">
                    
                    <!-- Editation Title -->
                    <h1 class="text-center mb-4 text-light">Edit Course</h1>
                    
                    <!-- Form for course editation -->    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        
                    <input type="hidden" name="id" value="<?php echo $_POST['id']?>">
                        
                        <!-- Course Data -->
                        <div class="form-group mb-3">
                                <label for="name" class="text-muted">Name:</label>
                                <input type="text" value="<?php echo htmlspecialchars($kurz['nazev']);?>" class="form-control bg-dark text-light" id="name" name="name">
                        </div>

                        <div class="form-group mb-3">
                            <label for="capacity" class="text-muted">Capacity:</label>
                            <input type="number" value="<?php echo htmlspecialchars($kurz['kapacita']);?>" class="form-control bg-dark text-light" id="capacity" name="capacity">
                        </div>

                        <!-- Date Interval Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="text-muted">Start Date:</label>
                                    <input type="date" value="<?php echo htmlspecialchars($kurz['datum_zacatku']);?>" class="form-control bg-dark text-light" id="start_date" name="start_date">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="text-muted">End Date:</label>
                                    <input type="date" value="<?php echo htmlspecialchars($kurz['datum_konce']);?>" class="form-control bg-dark text-light" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="text-center mt-2 mb-3"> 
                            <button type="submit" class="btn btn-primary center">Update Course</button>
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>