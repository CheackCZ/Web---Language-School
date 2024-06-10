<?php
    // init session
    session_start(); // Start the session
    
    // Include connection file
    include '../../inc/connection.php';

    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("Location: ../login/login.php");
        exit();
    }

    // Initialize variables to avoid undefined variable issues
    $student = [
        'id' => '',
        'jmeno' => '',
        'prijmeni' => '',
        'email' => '',
        'uzivjm' => '',
        'heslo' => ''
    ];

    // Check if the student_id is set in POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
        
        $student_id = intval($_POST['student_id']);

        // Fetch student details from the database
        $sql = "SELECT * FROM student WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $student = $result->fetch_assoc();
        } else {
            echo "Student not found.";
            $stmt->close();
            $conn->close();
            exit();
        }
        $stmt->close();
    
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        
        // Handle the form submission to update the student information
        $student_id = intval($_POST['id']);
        $name = $_POST['name'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "UPDATE student SET jmeno = ?, prijmeni = ?, email = ?, uzivjm = ?, heslo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $lname, $email, $username, $password, $student_id);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            echo "Student updated successfully.";
            header("location:../dashes/admin_dash.php");
        } else if ($stmt->affected_rows === 0) {
            echo "Nothing was changed!";
            header("location:../dashes/admin_dash.php");
        } else {
            echo "Failed to update student.";
        }

        $stmt->close();
        $conn->close();
        exit();
    
    } else {
        echo "Invalid request.";
        $conn->close();
        exit();
    }

    $conn->close();
?>

<!-- Form for editing student -->
<!doctype html>

    <!-- HEAD -->
    <html>
        <head>
            <meta charset="utf-8">
            
            <!-- Title -->
            <title>Edit Student</title>
            
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
                    background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
                }
            </style>
        </head>

        <!-- BODY -->
        <body class="gradient-custom">
            
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-dark">
                
                <div class="container">
                    
                    <!-- Logo -->
                    <a class="navbar-brand d-flex align-items-center">
                        <img src="../../img/book.svg" height="24" alt="JK Logo" loading="lazy" style="margin-top: -1px;" />
                        <p class="text-white ps-2 mb-0">Language School</p>
                    </a>
                    
                    <!-- Navbar Links -->
                    <div class="d-flex align-items-center">
                        <button data-mdb-ripple-init type="button" class="btn btn-primary me-3" onclick="location.href='../dashes/admin_dash.php'">(Admin) Dashboard</button>
                    </div>
                
                </div>

            </nav>

            <!-- Main -->
            <main class="vh-80">

                <div class="container form-control center w-50 bg-dark pt-5 pb-3 px-3 mt-5 shadow border-0" style="border-radius: 1rem;">

                    <!-- Editation Title -->
                    <h1 class="text-center mb-4 text-light">Edit Student</h1>

                    <!-- Form for student editation -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                       
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">

                        <!-- Student Data -->
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="text-muted">Name:</label>
                                    <input type="text" value="<?php echo htmlspecialchars($student['jmeno']); ?>" class="form-control bg-dark text-light" id="name" name="name">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="lname" class="text-muted">Last Name:</label>
                                    <input type="text" value="<?php echo htmlspecialchars($student['prijmeni']); ?>" class="form-control bg-dark text-light" id="lname" name="lname">
                                </div>
                            </div>
                        
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="text-muted">Email:</label>
                            <input type="email" value="<?php echo htmlspecialchars($student['email']); ?>" class="form-control bg-dark text-light" id="email" name="email">
                        </div>

                        <!-- User Login Data -->
                        <div class="form-group mb-3">
                            <label for="username" class="text-muted">Username:</label>
                            <input type="text" value="<?php echo htmlspecialchars($student['uzivjm']); ?>" class="form-control bg-dark text-light" id="username" name="username">
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="text-muted">Password:</label>
                            <input type="password" value="<?php echo htmlspecialchars($student['heslo']); ?>" class="form-control bg-dark text-light" id="password" name="password">
                        </div>
                        <div class="form-group mb-4">
                            <label for="confirm_password" class="text-muted">Confirm Password:</label>
                            <input type="password" value="<?php echo htmlspecialchars($student['heslo']); ?>" class="form-control bg-dark text-light" id="confirm_password" name="confirm_password">
                        </div>

                        <!-- Submit button -->
                        <div class="text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-primary center">Update Student</button>
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>