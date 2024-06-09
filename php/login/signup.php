<!-- Signup Process -->
<?php
    // Include connection file
    require_once '../../inc/connection.php';

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Check if user details are empty
        if (empty(trim($_POST["name"])) || empty(trim($_POST["lname"])) || empty(trim($_POST["email"]))) {
            echo "Please enter user data!";
            exit();
        } else {
            $name = trim($_POST["name"]);
            $lname = trim($_POST["lname"]);
            $email = trim($_POST["email"]);
        }

        // Check if username is empty
        if (empty(trim($_POST["username"]))) {
            echo "Please enter username!";
            exit();
        } else {
            $username = trim($_POST["username"]);
        }   
        
        // Check if password is empty
        if (empty(trim($_POST["password"]))) {
            echo "Please enter password!";
            exit();
        } else {
            $password = trim($_POST["password"]);
        }

        // Check input errors before inserting in database
        if (!empty($name) && !empty($lname) && !empty($email) && !empty($username) && !empty($password)) {
            
            // Prepare statement for select to check if username already exists
            $sql = "SELECT uzivjm FROM Student WHERE uzivjm = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) { 
                // Username doesn't exist, insert new user
                $sql = "INSERT INTO Student (jmeno, prijmeni, email, uzivjm, heslo, admin) VALUES (?, ?, ?, ?, ?, 0)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $lname, $email, $username, $password);

                if ($stmt->execute()) {
                    // Start a session and store user information
                    session_start();
                    $_SESSION['uzivjm'] = $username;
                    $_SESSION['admin'] = 0;

                    // Redirect to the app page
                    header("location: ../app.php");
                    exit();
                } else {
                    echo "Something went wrong. Please try again later.";
                }
            } else {
                echo "This username is already taken.";
            }

            $stmt->close();
            $conn->close();
        }
    }
?>

<!-- Form for login -->
<!doctype html>

    <html>
        <!-- Head -->
        <head>
            <meta charset="utf-8">

            <!-- Title -->
            <title>Sign Up</title>

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
                    <a class="navbar-brand d-flex align-items-center" href="../../index.php">
                        <img src="../../img/book.svg" height="24" alt="JK Logo" loading="lazy" style="margin-top: -1px;" />
                        <p class="text-white ps-2 mb-0">Language School</p>
                    </a>
                
                    <!-- Login link -->
                    <div class="d-flex align-items-center">
                        <button data-mdb-ripple-init type="button" class="btn btn-primary me-3" onclick="location.href='login.php'">Log In</button>
                    </div>
                
                </div>
            </nav>

            <!-- Main -->
            <main class="vh-80">
            
                <!-- Sign Up div -->
                <div class="container form-control center w-50 bg-dark pt-5 pb-3 px-3 mt-5 shadow border-0" style="border-radius: 1rem;">
                    
                    <h1 class="text-center mb-4 text-light">Sign Up</h1>
                    
                    <!-- Form for Sign Up --> 
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        
                        <!-- User Data -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="text-muted">Name:</label>
                                    <input type="text" class="form-control bg-dark text-light" id="name" name="name">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="lname" class="text-muted">Last Name:</label>
                                    <input type="text" class="form-control bg-dark text-light" id="lname" name="lname">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email" class="text-muted">Email:</label>
                            <input type="email" class="form-control bg-dark text-light" id="email" name="email">
                        </div>

                        <!-- User Login Data -->
                        <div class="form-group mb-3">
                            <label for="username" class="text-muted">Username:</label>
                            <input type="text" class="form-control bg-dark text-light" id="username" name="username">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password" class="text-muted">Password:</label>
                            <input type="password" class="form-control bg-dark text-light" id="password" name="password">
                        </div>
                        <div class="form-group mb-4">
                            <label for="confirm_password" class="text-muted">Confirm Password:</label>
                            <input type="password" class="form-control bg-dark text-light" id="confirm_password" name="confirm_password">
                        </div>

                        <!-- Login Link -->
                        <div class="form-group mb-3">
                            <p class="text-muted text-center">Already Signed-in? <a href="login.php">Log In</a></p>
                        </div>
                        
                        <!-- Submit button -->
                        <div class="text-center mt-2 mb-3"> 
                            <button type="submit" class="btn btn-primary center" href="../student/add_student.php">Sign Up</button>
                        </div>
                    
                    </form>
                
                </div>

            </main>
        </body>
    </html>