<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="#">
            NexTech Store
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../profile/profile.php">
                        Profil
                    </a>
                </li>

                <li class="nav-item">
                    <span class="navbar-text me-3">
                        👤 <?php echo $_SESSION['nama']; ?>
                    </span>
                </li>

                <li class="nav-item">
                    <a class="btn btn-outline-danger btn-sm" href="../../../logout.php">
                        Logout
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>