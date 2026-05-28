<?php

require "../config/database_connection.php";
include "../includes/header.php";
include "../includes/message.php";

/* =========================
   ADMIN AUTH CHECK
========================= */
if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

/* =========================
   FETCH COMPLAINT DETAILS
========================= */
$query = "
    SELECT complaints.*, users.full_name, users.email
    FROM complaints
    INNER JOIN users ON users.id = complaints.user_id
    WHERE complaints.id = '$id'
";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

/* If invalid ID */
if (!$data) {
    die("Complaint not found");
}

?>

<section class="section-5 bg-2">

    <div class="container py-5">

        <div class="row">

            <div class="col">

                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">

                    <ol class="breadcrumb mb-0">

                        <li class="breadcrumb-item">
                            <a href="dashboard.php">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Complaint Details
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

                        <h5 class="mb-1">Admin Panel</h5>

                        <p class="text-muted">
                            <?= htmlspecialchars($_SESSION['user_name']); ?>
                        </p>

                        <hr>

                        <a href="dashboard.php" class="btn btn-primary w-100 mb-2">
                            Dashboard
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
                            Complaint Details
                        </h3>

                        <p>
                            <b>User:</b>
                            <?= htmlspecialchars($data['full_name']); ?>
                            (<?= htmlspecialchars($data['email']); ?>)
                        </p>

                        <p>
                            <b>Title:</b>
                            <?= htmlspecialchars($data['title']); ?>
                        </p>

                        <p>
                            <b>Type:</b>
                            <?= htmlspecialchars($data['crime_type']); ?>
                        </p>

                        <p>
                            <b>Description:</b><br>
                            <?= nl2br(htmlspecialchars($data['description'])); ?>
                        </p>

                        <p>
                            <b>Status:</b>
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($data['status']); ?>
                            </span>
                        </p>

                        <p>
                            <b>Date:</b>
                            <?= $data['created_at']; ?>
                        </p>

                        <p>
                            <b>Evidence:</b><br>

                            <a href="../uploads/<?= $data['evidence_file']; ?>"
                               target="_blank"
                               class="btn btn-sm btn-info mt-2">

                                View File

                            </a>

                        </p>

                        <a href="dashboard.php" class="btn btn-secondary mt-3">
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>