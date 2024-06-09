<!-- Check of login -->
<?php
	// Include connection file
    require_once '../../inc/connection.php';

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Check if username is empty
        if (empty(trim($_POST["username"]))) {
            echo "Please enter username";
            exit();
        } else {
            $username = $_POST["username"];
        }	
        
        // Check if password is empty
		if (empty(trim($_POST["password"]))) {
            echo "Please enter password";
            exit();
        } else {
            $password = $_POST["password"];
        }

		// Check input errors before selecting from database
		if (!empty($username) && !empty($password)) {
			
			// Prepare statement for select "because there are parameters"
			$sql="select uzivjm, heslo, admin from Student where uzivjm = ?";
			$stmt=$conn->prepare($sql);
			$stmt->bind_param("s",$username);
			$stmt->execute();
			$result = $stmt->get_result();
				
			if ($result -> num_rows == 1) { 
				if($row = $result -> fetch_assoc()) {	

                    $saved_password = $row['heslo'];
					$admin = $row['admin'];

                    if($saved_password == $password){

                        session_start();

                        $_SESSION['uzivjm'] = $username;
						$_SESSION['admin'] = $admin;

                        if ($admin){
							header("location:../app.php");
							echo "welcome_admin";
						} else {
							header("location:../app.php");
							echo "welcome_user";
						}
					} else {
						echo "The password you entered was not valid.";
                        exit();
					}
				}
			} else {
				echo "No account found with that username";
                exit();
			}	
			$result->close();
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
            <title>Login</title>

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
                
                    <!-- Sign Up Link -->
                    <div class="d-flex align-items-center">
                        <button data-mdb-ripple-init type="button" onclick="location.href='signup.php'" class="btn btn-primary me-3">Sign up for free</button>
                    </div>
                
                </div>

            </nav>

            <!-- Main -->
            <main class="vh-80">
                
                <!-- Login div -->
                <div class="container form-control center w-50 bg-dark pt-5 pb-3 px-3 mt-5 shadow border-0" style="border-radius: 1rem;">
                    
                    <h1 class="text-center mb-4 text-light">Login</h1>
                    
                    <!-- Form for login -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        
                        <!-- User Login Data -->
                        <div class="form-group mb-3">
                            <label for="username" class="text-muted">Username:</label>
                            <input type="text" class="form-control bg-dark text-light" id="username" name="username">
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="password" class="text-muted">Password:</label>
                            <input type="password" class="form-control bg-dark text-light" id="password" name="password">
                        </div>

                        <!-- Sign Up Link -->
                        <div class="form-group mb-3">
                            <p class="text-muted text-center">Don't have an account? <a href="signup.php">Sign Up</a></p>
                        </div>
                        
                        <div class="text-center mt-2 mb-3"> 
                            <button type="submit" class="btn btn-primary center">Log In</button>
                        </div>
                    </form>
                
                </div>
        
            </main>
        </body>
    </html>