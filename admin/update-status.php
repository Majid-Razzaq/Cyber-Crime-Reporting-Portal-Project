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
   FETCH COMPLAINT
========================= */
$query = mysqli_query(
    $conn,
    "SELECT * FROM complaints WHERE id='$id'"
);

$data = mysqli_fetch_assoc($query);

/* Invalid ID check */
if (!$data) {
    die("Complaint not found");
}

/* =========================
   UPDATE STATUS
========================= */
if (isset($_POST['update_status'])) {

    $status = $_POST['status'];

    $update = mysqli_query(
        $conn,
        "UPDATE complaints 
         SET status='$status'
         WHERE id='$id'"
    );

    if ($update) {

        $_SESSION['success'] = "Status updated successfully";

        header("Location: dashboard.php");
        exit;
    } else {

        $_SESSION['error'] = "Failed to update status";
    }
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
                            Update Status
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
                            Update Complaint Status
                        </h3>

                        <p>
                            <b>Title:</b>
                            <?= htmlspecialchars($data['title']); ?>
                        </p>

                        <p>
                            <b>Current Status:</b>
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($data['status']); ?>
                            </span>
                        </p>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">
                                    Select Status
                                </label>

                                <select name="status" class="form-select" required>

                                    <option value="Pending"
                                        <?= $data['status'] == 'Pending' ? 'selected' : '' ?>>
                                        Pending
                                    </option>

                                    <option value="In Progress"
                                        <?= $data['status'] == 'In Progress' ? 'selected' : '' ?>>
                                        In Progress
                                    </option>

                                    <option value="Resolved"
                                        <?= $data['status'] == 'Resolved' ? 'selected' : '' ?>>
                                        Resolved
                                    </option>

                                    <option value="Rejected"
                                        <?= $data['status'] == 'Rejected' ? 'selected' : '' ?>>
                                        Rejected
                                    </option>

                                </select>

                            </div>

                            <button type="submit" name="update_status" class="btn btn-success">
                                Update Status
                            </button>

                            <a href="dashboard.php" class="btn btn-secondary">
                                Back
                            </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>