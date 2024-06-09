<!-- Check of details -->
<?php
	// Include connection file
    require_once '../../inc/connection.php';

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
        // Check if namename is empty
        if (empty(trim($_POST["name"]))) {
            echo "Please enter name";
            exit();
        }

        // Check if lname is empty
        if (empty(trim($_POST["lname"]))) {
            echo "Please enter last name";
            exit();
        }

        // Check if email is empty
        if (empty(trim($_POST["email"]))) {
            echo "Please enter email";
            exit();
        }

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

        // Check if confirmation of password is empty
		if (empty(trim($_POST["confirm_password"]))) {
            echo "Please enter confirmation of password";
            exit();
        } else {
            $password = $_POST["password"];
        }

        // Check if password matches the confirmation of password
        if ((trim($_POST["password"])) != (trim($_POST["confirm_password"]))) {
            echo "Password does not match confirmed password!";
            exit();
        }

        // Příprava příkazu: "prepared statement" .. pro příkaz insert, update, delete
        $sql = "insert into student(jmeno,prijmeni,uzivjm,email,heslo) value(?,?,?,?,?)";
        $stmt = $conn->prepare($sql); // Kontrola a předklad insertu

        // Set parametry
        $jmeno = $_POST["name"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
        $prijmeni = $_POST["lname"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
        $uzivjm = $_POST["username"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
        $email = $_POST["email"];
        $heslo = $_POST["password"]; // Druh je jméno list selectu, id je jedna z options

        // Bind parametry
        $stmt->bind_param("sssss", $jmeno, $prijmeni, $uzivjm, $email, $heslo);  // Vloží se místo ?
        
        // Execute
        $stmt->execute();

        // Redirect back to index.php
        header("Location:../dashes/admin_dash.php");

        // Close stmt and conn
        $stmt->close();
        $conn->close();
	}
?>

<!-- Form for login -->
<!doctype html>
    <html>
        <!-- HEAD -->
        <head>
            <meta charset="utf-8">

            <!-- Title -->
            <title>Add Student</title>

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
            <main class="vh-100">
            
                <div class="container form-control center w-50 bg-dark pt-5 pb-3 px-3 mt-5 shadow border-0" style="border-radius: 1rem;">

                    <!-- Addition Title -->
                    <h1 class="text-center mb-4 text-light">Add Student</h1>
                    
                    <!-- Form for student addition -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        
                        <!-- Student Data -->
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

                        <!-- Submit button -->
                        <div class="text-center mt-2 mb-3"> 
                            <button type="submit" class="btn btn-primary center">Add Student</button>
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>