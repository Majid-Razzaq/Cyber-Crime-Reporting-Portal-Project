<?php

require "../config/database_connection.php";
include "../includes/header.php";
include "../includes/message.php";

/* =========================
   ADMIN SESSION CHECK
========================= */
if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: ../auth/login.php");

    exit;
}

$admin_id = $_SESSION['user_id'];

/* =========================
   FETCH ALL COMPLAINTS
========================= */
$query = "
    SELECT 
        complaints.*,
        users.full_name,
        users.email
    FROM complaints
    INNER JOIN users ON users.id = complaints.user_id
    ORDER BY complaints.id DESC
";

$result = mysqli_query($conn, $query);

?>

<section class="section-5 bg-2">

    <div class="container py-5">

        <div class="row">

            <div class="col">

                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">

                    <ol class="breadcrumb mb-0">

                        <li class="breadcrumb-item">
                            <a href="dashboard.php">Home</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Admin Dashboard
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

                        <a href="users.php" class="btn btn-success w-100 mb-2">
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
                            All Complaints
                        </h3>

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover">

                                <thead class="table-dark">

                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                        <tr>

                                            <td><?= $row['id']; ?></td>

                                            <td>
                                                <?= htmlspecialchars($row['full_name']); ?><br>
                                                <small><?= htmlspecialchars($row['email']); ?></small>
                                            </td>

                                            <td><?= htmlspecialchars($row['title']); ?></td>

                                            <td><?= htmlspecialchars($row['crime_type']); ?></td>

                                            <td>
                                                <?php if ($row['status'] == 'Pending') { ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php } elseif ($row['status'] == 'In Progress') { ?>
                                                    <span class="badge bg-info">In Progress</span>
                                                <?php } elseif ($row['status'] == 'Resolved') { ?>
                                                    <span class="badge bg-success">Resolved</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php } ?>
                                            </td>

                                            <td><?= $row['created_at']; ?></td>

                                            <td>

                                                <a href="view-complaint.php?id=<?= $row['id']; ?>"
                                                    class="btn btn-info btn-sm">
                                                    View
                                                </a>

                                                <a href="update-status.php?id=<?= $row['id']; ?>"
                                                    class="btn btn-warning btn-sm">
                                                    Status
                                                </a>

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