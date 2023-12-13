            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Administrator</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="schedules.php?room_id=">
                                <div class="sb-nav-link-icon"><i class="fa-regular fa-calendar-check"></i></div>
                                Schedules
                            </a>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-calendar-days"></i></div>
                                Timetables
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="roomutil_timetables.php?room=">Room Utilization</a>
                                    <a class="nav-link" href="student_timetables.php?section=">Student Schedule</a>
                                    <a class="nav-link" href="faculty_timetables.php?faculty=">Faculty Schedule</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="course.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                                Course
                                <div class="sb-sidenav-collapse-arrow"></div>
                            </a>
                            <a class="nav-link" href="subjects.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Subjects
                                <div class="sb-sidenav-collapse-arrow"></div>
                            </a>
                            <?php if ( isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Administrator" ): ?>
                            <a class="nav-link" href="faculty.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Faculty
                                <div class="sb-sidenav-collapse-arrow"></div>
                            </a>
                            <?php endif; ?>
                            <a class="nav-link" href="section.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-puzzle-piece"></i></div>
                                Section
                                <div class="sb-sidenav-collapse-arrow"></div>
                            </a>
                            <a class="nav-link" href="rooms.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-school-flag"></i></div>
                                Room
                                <div class="sb-sidenav-collapse-arrow"></div>
                            </a>
                            <?php if ( isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Administrator" ): ?>
                            <a class="nav-link" href="users.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                                Users
                                <div class="sb-sidenav-collapse-arrow"></div>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
            </div>