<!-- Check of login -->
<?php
	// Include connection file
    require_once '../../inc/connection.php';

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Check if name is empty
        if (empty(trim($_POST["name"]))) {
            echo "Please enter the course name";
            exit();
        } else {
            $name = $_POST["name"];
        }	
        
        // Check if capacity is empty
		if (empty(trim($_POST["capacity"]))) {
            echo "Please enter capacity";
            exit();
        } else {
            $capacity = $_POST["capacity"];
        }

        // Check if start date is empty
		if (empty(trim($_POST["start_date"]))) {
            echo "Please enter start date of course";
            exit();
        } else {
            $start_date = $_POST["start_date"];
        }

        // Check if end date is empty
		if (empty(trim($_POST["end_date"]))) {
            echo "Please enter end date of course";
            exit();
        } else {
            $end_date = $_POST["end_date"];
        }

        // Příprava příkazu: "prepared statement" .. pro příkaz insert, update, delete
        $sql = "insert into kurz(nazev,kapacita,datum_zacatku,datum_konce) value(?,?,?,?)";
        $stmt = $conn->prepare($sql); // Kontrola a předklad insertu

        // Set parametry
        $nazev = $_POST["name"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
        $kapacita = $_POST["capacity"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
        $datum_zacatku = $_POST["start_date"]; // Hodnota z input named cesky pres pole $_POST do naší proměnné
        $datum_konce = $_POST["end_date"];

        // Bind parametry
        $stmt->bind_param("siss", $nazev, $kapacita, $datum_zacatku, $datum_konce);  // Vloží se místo ?
        
        // Execute
        $stmt->execute();

        // Close stmt and conn
        $stmt->close();
        $conn->close();

        header("location: ../dashes/admin_dash.php");
	}
?>

<!-- Form for login -->
<!doctype html>
    <html>
        <!-- Head -->
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
                        <button data-mdb-ripple-init type="button" class="btn btn-primary me-3" onclick="location.href='../dashes/admin_dash.php'">(Admin) Dashboard</button>
                    </div>
                
                </div>
            </nav>

            <!-- Main -->
            <main class="vh-80">
            
                <div class="container form-control center w-50 bg-dark pt-5 pb-3 px-3 mt-5 shadow border-0" style="border-radius: 1rem;">
                    
                    <!-- Addition Title -->
                    <h1 class="text-center mb-4 text-light">Add Course</h1>
                    
                    <!-- Form for course addition -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        
                        <!-- Course Data -->
                        <div class="form-group mb-3">
                                <label for="name" class="text-muted">Name:</label>
                                <input type="text" class="form-control bg-dark text-light" id="name" name="name">
                        </div>

                        <div class="form-group mb-3">
                            <label for="capacity" class="text-muted">Capacity:</label>
                            <input type="number" class="form-control bg-dark text-light" id="capacity" name="capacity">
                        </div>

                        <!-- Date Interval Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="text-muted">Start Date:</label>
                                    <input type="date" class="form-control bg-dark text-light" id="start_date" name="start_date">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="text-muted">End Date:</label>
                                    <input type="date" class="form-control bg-dark text-light" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit button -->
                        <div class="text-center mt-2 mb-3"> 
                            <button type="submit" class="btn btn-primary center">Add Course</button>
                        </div>
                    </form>
                </div>
            </main>
        </body>
    </html>