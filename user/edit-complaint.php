<?php

require "../config/database_connection.php";
include "../includes/header.php";

// Check Login
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$complaint_id = $_GET['id'];

/* Fetch complaint (security: only user’s own complaint) */
$complaint_res = mysqli_query(
    $conn,
    "SELECT * FROM complaints 
     WHERE id = '$complaint_id' AND user_id = '$user_id'"
);

$complaint = mysqli_fetch_assoc($complaint_res);

if (!$complaint) {
    die("Invalid Complaint");
}

/* Update Complaint */
if (isset($_POST['update'])) {

    $title         = mysqli_real_escape_string($conn, trim($_POST['title']));
    $crime_type    = mysqli_real_escape_string($conn, trim($_POST['crime_type']));
    $description   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $incident_date = mysqli_real_escape_string($conn, trim($_POST['incident_date']));

    mysqli_query(
        $conn,
        "UPDATE complaints SET
            title = '$title',
            crime_type = '$crime_type',
            description = '$description',
            incident_date = '$incident_date'
         WHERE id = '$complaint_id' AND user_id = '$user_id'"
    );

    $_SESSION['success'] = "Complaint updated successfully.";

    header("Location: dashboard.php");
    exit;
}

?>

<section class="py-5 bg-light">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <!-- Card -->
                <div class="card shadow border-0">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h3 class="mb-0">
                                Edit Complaint
                            </h3>

                            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                                Back
                            </a>

                        </div>

                        <form method="POST">

                            <!-- Title -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Complaint Title
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    value="<?= htmlspecialchars($complaint['title']); ?>"
                                    required>

                            </div>

                            <!-- Crime Type -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Crime Type
                                </label>

                                <select name="crime_type" class="form-select" required>

                                    <option value="Hacking" 
                                        <?= $complaint['crime_type'] == 'Hacking' ? 'selected' : '' ?>>
                                        Hacking
                                    </option>

                                    <option value="Online Fraud" 
                                        <?= $complaint['crime_type'] == 'Online Fraud' ? 'selected' : '' ?>>
                                        Online Fraud
                                    </option>

                                    <option value="Cyber Bullying" 
                                        <?= $complaint['crime_type'] == 'Cyber Bullying' ? 'selected' : '' ?>>
                                        Cyber Bullying
                                    </option>

                                    <option value="Phishing" 
                                        <?= $complaint['crime_type'] == 'Phishing' ? 'selected' : '' ?>>
                                        Phishing
                                    </option>

                                    <option value="Fake Account" 
                                        <?= $complaint['crime_type'] == 'Fake Account' ? 'selected' : '' ?>>
                                        Fake Account
                                    </option>

                                </select>

                            </div>

                            <!-- Description -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="5"
                                    required><?= htmlspecialchars($complaint['description']); ?></textarea>

                            </div>

                            <!-- Incident Date -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Incident Date
                                </label>

                                <input
                                    type="date"
                                    name="incident_date"
                                    class="form-control"
                                    value="<?= $complaint['incident_date']; ?>"
                                    required>

                            </div>

                            <!-- Submit -->
                            <button type="submit" name="update" class="btn btn-primary">
                                Update Complaint
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>