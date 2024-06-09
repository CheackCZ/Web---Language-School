<?php
    session_start();
    $is_logged_in = isset($_SESSION['uzivjm']);
    $is_admin = isset($_SESSION['admin']) && $_SESSION['admin'];

    $username = $is_logged_in ? $_SESSION['uzivjm'] : '';
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
            <link rel="icon" type="image/svg+xml" href="../img/book.svg">

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        </head>

        <!-- BODY -->
        <body>
            <!-- Header -->
            <header>
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
                    
                    <div class="container">

                        <!-- Logo -->
                        <a class="navbar-brand d-flex align-items-center" href="app.php">
                            <img src="../img/book.svg" height="24" alt="JK Logo" loading="lazy" style="margin-top: -1px;" />
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
                                    <a class="nav-link" href="login/logout.php">Logout</a>
                                </li>
                                <li class="nav-item">
                                    <?php
                                        if ($is_admin) {
                                            echo '<a class="nav-link" href="dashes/admin_dash.php">(Admin) Dashboard</a>';
                                        } else if ($is_logged_in) {
                                            echo '<a class="nav-link" href="dashes/user_dash.php">(User) Dashboard</a>';
                                        }
                                    ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                
                </nav>
            </header>

            <!-- Main -->
            <main class="bg-light">

                <!-- Landing Page Title -->
                <div class="text-center pt-5 mt-5">
                    <h1 class="mt-3">Welcome back,
                        <?php
                            echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
                        ?>    
                    </h1>
                </div>

                <!-- Section with Cards -->
                <section class="container pb-4">

                    <!-- Card "About School" -->
                    <div class="card my-5 shadow border-0" style="border-radius: 1rem;" height="400px">
                        
                        <div class="row align-items-center">
                        
                            <div class="col-md-6">
                                <div class="card-body  ps-5 pe-5">
                                    <h2>About School</h2>
                                    <p>Welcome to our Language School's 'About School' section, your gateway to understanding the essence of our institution. Uncover the story behind our school, 
                                        from its inception to its current standing as a hub of language learning. Explore the values that drive us, the community we've built, and our dedication 
                                        to providing top-notch language education. Discover what sets us apart and why students choose us as their language learning destination.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <img src="../img/jazykova_skola.jpeg" class="card-img-top" alt="Placeholder Image">
                            </div>
                        
                        </div>

                    </div>
                    
                    <!-- Card "Courses" -->
                    <div class="card my-5 shadow border-0" style="border-radius: 1rem;" height="400px">
                       
                        <div class="row align-items-center">
                        
                            <div class="col-md-6">
                                <img src="../img/people.jpg" class="card-img-top" alt="Placeholder Image">
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card-body  pe-5">
                                    <h2>Courses</h2>
                                    <p>Explore the myriad of language learning opportunities available in our 'Courses' section. From introductory classes to advanced seminars, we offer a 
                                        comprehensive range of programs tailored to meet your language learning needs. Dive into the details of each course, including class schedules, 
                                        curriculum overviews, and instructor profiles. Whether you're a beginner or an experienced learner, there's a course for you to embark on your language 
                                        <label for=""></label>learning journey.</p>
                                </div>
                            </div>
                        
                        </div>
                    
                    </div>
                    
                    <!-- Card "Mission" -->
                    <div class="card my-5 shadow border-0" style="border-radius: 1rem;" height="400px">
                        
                        <div class="row align-items-center">
                        
                            <div class="col-md-6">
                                <div class="card-body ps-5 pe-5">
                                    <h2>Mission</h2>
                                    <p>Delve into the heart of our mission in the 'Mission' section of our webapp. Learn about our commitment to fostering linguistic proficiency, cultural 
                                        understanding, and global communication. Discover how we strive to create a supportive and inclusive learning environment where students from all
                                        backgrounds can thrive. Explore our vision for the future of language education and our dedication to empowering individuals to reach their full 
                                        potential through language learning.</p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <img src="../img/mission.jpg" class="card-img-top" alt="Placeholder Image">
                            </div>
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