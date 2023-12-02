        <script src="js/scripts.js"></script>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php" title="EVSU-TC Subject Scheduling System">EVSU-TC SSS</a>
            <?php if ( isset($row['user_type']) && $row['user_type'] == "Administrator" ): ?>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <?php endif; ?>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                </div>
            </form>
            <!-- Navbar-->
            <?php
            if(isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
                // Session ID has a value
                echo '<p style="color: white; margin-bottom: 0rem !important; margin-right: 5px;">'.$_SESSION['fullname'].'</p>';
            }
            ?>
            <?php
            if(isset($_SESSION['emp_id']) && !empty($_SESSION['emp_id'])) {
                // Session ID has a value
                echo '<p style="color: white; margin-bottom: 0rem !important; margin-right: 5px;">'.$_SESSION['name'].'</p>';
            }
            ?>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <?php
                        if(isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
                            // Session ID has a value
                            echo '<li><a class="dropdown-item" href="#">Settings</a></li>';
                        }
                        ?>
                        <?php
                        if(isset($_SESSION['emp_id']) && !empty($_SESSION['emp_id'])) {
                            // Session ID has a value
                            echo '<li><a class="dropdown-item" href="#">Settings</a></li>';
                        }
                        ?>
                        <li><hr class="dropdown-divider" /></li>
                        <?php
                        if(isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
                            // Session ID has a value
                            echo '<li><a class="dropdown-item" href="include/logout.php">Logout</a></li>';
                        }
                        ?>
                        <?php
                        if(isset($_SESSION['emp_id']) && !empty($_SESSION['emp_id'])) {
                            // Session ID has a value
                            echo '<li><a class="dropdown-item" href="include/logoutf.php">Logout</a></li>';
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>