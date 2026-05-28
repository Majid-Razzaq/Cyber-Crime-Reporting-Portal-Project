<?php

require "../config/database_connection.php";
include "../includes/header.php";

// Check Login
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Add Complaint
if (isset($_POST['submit'])) {

    $title         = mysqli_real_escape_string($conn, trim($_POST['title']));
    $crime_type    = mysqli_real_escape_string($conn, trim($_POST['crime_type']));
    $description   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $incident_date = mysqli_real_escape_string($conn, trim($_POST['incident_date']));

    // File Upload
    $file_name = $_FILES['evidence']['name'];
    $tmp_name  = $_FILES['evidence']['tmp_name'];

    // Unique File Name
    $new_file_name = time() . "_" . $file_name;

    move_uploaded_file(
        $tmp_name,
        "../uploads/$new_file_name"
    );

    // Insert Query
    $query = mysqli_query(
        $conn,
        "INSERT INTO complaints
        (
            user_id,
            title,
            crime_type,
            description,
            incident_date,
            evidence_file
        )

        VALUES
        (
            '$user_id',
            '$title',
            '$crime_type',
            '$description',
            '$incident_date',
            '$new_file_name'
        )"
    );

    if ($query) {

        $_SESSION['success'] = "Complaint submitted successfully.";

        header("Location: dashboard.php");
        exit;
    } else {

        $_SESSION['error'] = "Failed to submit complaint.";
    }
}

?>

<section class="py-5 bg-light">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

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

                <div class="card shadow border-0">

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h3 class="mb-0">
                                Add Complaint
                            </h3>

                            <a href="dashboard.php" class="btn btn-secondary btn-sm">
                                Back
                            </a>

                        </div>

                        <form method="POST" enctype="multipart/form-data">

                            <!-- Title -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Complaint Title
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    placeholder="Enter complaint title"
                                    required>

                            </div>

                            <!-- Crime Type -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Crime Type
                                </label>

                                <select
                                    name="crime_type"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Crime Type
                                    </option>

                                    <option>
                                        Hacking
                                    </option>

                                    <option>
                                        Online Fraud
                                    </option>

                                    <option>
                                        Cyber Bullying
                                    </option>

                                    <option>
                                        Phishing
                                    </option>

                                    <option>
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
                                    placeholder="Enter complaint details"
                                    required></textarea>

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
                                    required>

                            </div>

                            <!-- Evidence -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Upload Evidence
                                </label>

                                <input
                                    type="file"
                                    name="evidence"
                                    class="form-control"
                                    required>

                            </div>

                            <!-- Button -->
                            <button
                                type="submit"
                                name="submit"
                                class="btn btn-primary">

                                Submit Complaint

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>