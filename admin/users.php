<?php

require "../config/database_connection.php";
require "../includes/auth.php";

requireAdmin();

include "../includes/header.php";
include "../includes/message.php";

/* =========================
   FETCH USERS
========================= */
$query = mysqli_query(
    $conn,
    "SELECT * FROM users ORDER BY id DESC"
);

?>

<section class="section-5 bg-2">

    <div class="container py-5">

        <div class="row">

            <div class="col">

                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">

                    <ol class="breadcrumb mb-0">

                        <li class="breadcrumb-item">
                            <a href="dashboard.php">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Users
                        </li>

                    </ol>

                </nav>

            </div>

        </div>

        <div class="row">

            <!-- Sidebar -->
            <div class="col-lg-3">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <h5 class="mb-1">
                            Admin Panel
                        </h5>

                        <p class="text-muted">
                            <?= htmlspecialchars($_SESSION['user_name']); ?>
                        </p>

                        <hr>

                        <a href="dashboard.php" class="btn btn-primary w-100 mb-2">
                            Dashboard
                        </a>

                        <a href="users.php" class="btn btn-dark w-100 mb-2">
                            Users
                        </a>

                        <a href="../auth/logout.php" class="btn btn-danger w-100">
                            Logout
                        </a>

                    </div>

                </div>

            </div>

            <!-- Content -->
            <div class="col-lg-9">

                <?php displayMessage(); ?>

                <div class="card shadow border-0">

                    <div class="card-body p-4">

                        <h3 class="mb-4">
                            All Users
                        </h3>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover">

                                <thead class="table-dark">

                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Registered</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                                        <tr>

                                            <td>
                                                <?= $row['id']; ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['full_name']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['email']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['phone']); ?>
                                            </td>

                                            <td>
                                                <?= $row['created_at']; ?>
                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>