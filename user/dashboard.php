<?php

require "../config/database_connection.php";
include "../includes/header.php";

// Check user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Update Profile
if (isset($_POST['update_profile'])) {

    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $phone     = mysqli_real_escape_string($conn, trim($_POST['phone']));

    if (empty($full_name) || empty($phone)) {

        $_SESSION['error'] = "All fields are required.";
    } else {

        $update = mysqli_query(
            $conn,
            "UPDATE users 
             SET 
                full_name='$full_name',
                phone='$phone'
             WHERE id='$user_id'"
        );

        if ($update) {

            $_SESSION['success'] = "Profile updated successfully.";

            // Update session name
            $_SESSION['user_name'] = $full_name;

            header("Location: dashboard.php");
            exit;
        } else {

            $_SESSION['error'] = "Profile update failed.";
        }
    }
}

// Fetch user info
$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$user_id'"
);

$user = mysqli_fetch_assoc($query);

?>

<section class="py-5 bg-light">

    <div class="container">

        <div class="row">

            <!-- Sidebar -->
            <div class="col-lg-3">

                <div class="card shadow border-0">

                    <div class="card-body text-center">

                        <!-- Welcome Text -->
                        <h4 class="fw-bold mb-1">
                            Welcome Back
                        </h4>

                        <p class="text-muted mb-3">
                            <?= htmlspecialchars($_SESSION['user_name']); ?>
                        </p>

                        <hr>


                        <a href="dashboard.php" class="btn btn-primary w-100 mb-2">
                            Dashboard
                        </a>

                        <a href="add-complaint.php" class="btn btn-success w-100 mb-2">
                            Add Complaint
                        </a>

                        <a href="../auth/logout.php" class="btn btn-danger w-100">
                            Logout
                        </a>

                    </div>

                </div>

            </div>

            <!-- Main Content -->
            <div class="col-lg-9">

                <!-- Messages -->
                <?php if (isset($_SESSION['success'])) { ?>

                    <div class="alert alert-success">
                        <?= $_SESSION['success']; ?>
                    </div>

                <?php unset($_SESSION['success']);
                } ?>

                <?php if (isset($_SESSION['error'])) { ?>

                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; ?>
                    </div>

                <?php unset($_SESSION['error']);
                } ?>

                <!-- Profile Card -->
                <div class="card shadow border-0 mb-4">

                    <form method="POST">

                        <div class="card-body p-4">

                            <h3 class="mb-4">
                                My Profile
                            </h3>

                            <div class="mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    name="full_name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['full_name']); ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['email']); ?>"
                                    readonly>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['phone']); ?>"
                                    required>

                            </div>

                        </div>

                        <div class="card-footer bg-white p-4">

                            <button
                                type="submit"
                                name="update_profile"
                                class="btn btn-primary">
                                Update Profile
                            </button>

                        </div>

                    </form>

                </div>

                <!-- Complaint Section -->
                <div class="card shadow border-0">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h3 class="mb-0">
                                My Complaints
                            </h3>

                            <a href="add-complaint.php" class="btn btn-success">
                                Add Complaint
                            </a>

                        </div>

                        <?php

                        $complaints = mysqli_query(
                            $conn,
                            "SELECT * FROM complaints 
                             WHERE user_id='$user_id'
                             ORDER BY id DESC"
                        );

                        if (mysqli_num_rows($complaints) > 0) {

                        ?>

                            <table class="table table-bordered">

                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Crime Type</th>
                                    <th>Status</th>
                                    <th>Evidence</th>
                                    <th>Action</th>
                                </tr>

                                <?php while ($row = mysqli_fetch_assoc($complaints)) { ?>

                                    <tr>

                                        <td><?= $row['id']; ?></td>

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

                                        <td>

                                            <a
                                                href="../uploads/<?= $row['evidence_file']; ?>"
                                                target="_blank"
                                                class="btn btn-sm btn-info">
                                                View
                                            </a>

                                        </td>

<td>

    <?php if ($row['status'] == 'Pending' || $row['status'] == 'In Progress') { ?>

        <a
            href="edit-complaint.php?id=<?= $row['id']; ?>"
            class="btn btn-sm btn-warning">
            Edit
        </a>

        <a
            href="delete-complaint.php?id=<?= $row['id']; ?>"
            class="btn btn-sm btn-danger"
            onclick="return confirm('Are you sure?')">
            Delete
        </a>

    <?php } else { ?>

        <button class="btn btn-sm btn-secondary" disabled>
            Locked
        </button>

    <?php } ?>

</td>

                                    </tr>

                                <?php } ?>

                            </table>

                        <?php } else { ?>

                            <div class="alert alert-info">
                                No complaints found.
                            </div>

                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>