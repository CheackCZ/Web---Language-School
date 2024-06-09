<?php
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Start the session
    session_start();

    // Include the connection file
    include '../../inc/connection.php';

    // Check if the user is logged in and is an admin
    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        header("Location: ../login/login.php");
        exit();
    }

    // Initialize variables
    $zapis = [
        'id' => '',
        'uzivjm' => '',
        'kurz_id' => '',
        'datum_zapisu' => ''
    ];

    // Fetch all courses for the dropdown menu
    $courses = [];
    $sql_courses = "SELECT id, nazev FROM Kurz";
    $result_courses = $conn->query($sql_courses);
    if ($result_courses->num_rows > 0) {
        while($row = $result_courses->fetch_assoc()) {
            $courses[] = $row;
        }
    }

    // Fetch all users for the dropdown menu
    $users = [];
    $sql_users = "SELECT id, uzivjm FROM Student";
    $result_users = $conn->query($sql_users);
    if ($result_users->num_rows > 0) {
        while($row = $result_users->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Check if the zapis_id is set in POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['zapis_id'])) {
        
        $zapis_id = intval($_POST['zapis_id']);

        // Fetch zapis details from the database
        $sql = "SELECT Zapis.id, Student.uzivjm, Zapis.kurz_id, Zapis.datum_zapisu FROM Zapis 
                JOIN Student ON Zapis.student_id = Student.id 
                WHERE Zapis.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $zapis_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $zapis = $result->fetch_assoc();
        } else {
            echo "Zápis not found.";
            $stmt->close();
            $conn->close();
            exit();
        }
        $stmt->close();
    
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        
        // Handle the form submission to update the zapis information
        $zapis_id = intval($_POST['id']);
        $username = $_POST['username'];
        $kurz_id = intval($_POST['kurz_id']);
        $datum_zapisu = $_POST['datum_zapisu'];

        // Fetch the student ID based on the username
        $sql_student = "SELECT id FROM Student WHERE uzivjm = ?";
        $stmt_student = $conn->prepare($sql_student);
        $stmt_student->bind_param("s", $username);
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();

        if ($result_student->num_rows == 1) {
            $student = $result_student->fetch_assoc();
            $student_id = $student['id'];
        } else {
            echo "Student not found.";
            $stmt_student->close();
            $conn->close();
            exit();
        }
        $stmt_student->close();

        // Update the zapis record
        $sql = "UPDATE Zapis SET student_id = ?, kurz_id = ?, datum_zapisu = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $student_id, $kurz_id, $datum_zapisu, $zapis_id);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            echo "Zápis updated successfully.";
            header("location:../dashes/admin_dash.php");
        } else if ($stmt->affected_rows === 0) {
            echo "Nothing was changed!";
            header("location:../dashes/admin_dash.php");
        } else {
            echo "Failed to update zápis.";
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

<!-- Form for editing zapis -->
<!doctype html>
    <html>
        
        <!-- HEAD -->
        <head>
            <meta charset="utf-8">

            <!-- Title -->
            <title>Edit Zápis</title>

            <!-- Favicon -->
            <link rel="icon" type="image/svg+xml" href="../../img/book.svg">
            
            <!-- Bootstrap Links -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            
            <!-- Custom css -->
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
                    <a class="navbar-brand d-flex align-items-center" href="../app.php">
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
                    <h1 class="text-center mb-4 text-light">Edit Zápis</h1>
                    
                    <!-- Form for entry editation -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                       
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($zapis['id']); ?>">
                        
                        <!-- User's Data -->
                        <div class="form-group mb-3">
                            <label for="username" class="text-muted">Username:</label>
                            <select class="form-control bg-dark text-light" id="username" name="username">
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['uzivjm']; ?>" <?php if ($user['uzivjm'] == $zapis['uzivjm']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($user['uzivjm']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <!-- Course Data -->
                        <div class="form-group mb-3 custom-select-wrapper">
                            <label for="kurz_id" class="text-muted">Kurz:</label>
                            <select class="form-control bg-dark text-light custom-select" id="kurz_id" name="kurz_id">
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['id']; ?>" <?php if ($course['id'] == $zapis['kurz_id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($course['nazev']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="datum_zapisu" class="text-muted">Datum Zapisu:</label>
                            <input type="date" value="<?php echo htmlspecialchars($zapis['datum_zapisu']); ?>" class="form-control bg-dark text-light" id="datum_zapisu" name="datum_zapisu">
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-2 mb-3">
                            <button type="submit" class="btn btn-primary center">Update Entry</button>
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>