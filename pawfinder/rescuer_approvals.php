
<?php
session_start();
require_once 'config.php';

// Admin access only
if (
    empty($_SESSION['logged_in']) ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    header("Location: login.php");
    exit();
}

// CSRF protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = $_SESSION['approval_message'] ?? '';
unset($_SESSION['approval_message']);

// Handle approval actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        die("Invalid security token.");
    }

    $user_id = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($user_id <= 0 || !in_array($action, ['approve', 'reject'], true)) {
        $_SESSION['approval_message'] = "Invalid request.";
        header("Location: rescuer_approvals.php");
        exit();
    }

    $new_status = ($action === 'approve') ? 'Approved' : 'Rejected';

    // Only update Rescuer accounts
    $stmt = $conn->prepare(
        "UPDATE users
         SET account_status = ?
         WHERE id = ?
         AND role = 'Rescuer'
         AND account_status = 'Pending'"
    );

    $stmt->bind_param("si", $new_status, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['approval_message'] =
            "Rescuer account successfully " . strtolower($new_status) . ".";
    } else {
        $_SESSION['approval_message'] =
            "No pending Rescuer account was updated.";
    }

    $stmt->close();

    header("Location: rescuer_approvals.php");
    exit();
}

// Get pending Rescuer accounts
$result = $conn->query(
    "SELECT id, fullname, email, address, contact, username, created_at
     FROM users
     WHERE role = 'Rescuer'
     AND account_status = 'Pending'
     ORDER BY created_at DESC"
);

if (!$result) {
    die("Unable to retrieve accounts.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pawfinder - Rescuer Approvals</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">🐾 Rescuer Account Approvals</h2>
            <p class="text-muted mb-0">
                Review and manage new rescuer registrations.
            </p>
        </div>

        <a href="admin.php" class="btn btn-secondary">
            ← Back to Admin
        </a>
    </div>

    <?php if ($message !== ''): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Pending Applications
                <span class="badge bg-warning text-dark">
                    <?= $result->num_rows ?>
                </span>
            </h5>

            <?php if ($result->num_rows === 0): ?>

                <div class="alert alert-success mb-0">
                    No pending Rescuer applications.
                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Registered</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php while ($row = $result->fetch_assoc()): ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($row['fullname']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['username']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['address']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['contact']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['created_at'] ?? '') ?>
                                </td>

                                <td>
                                    <form method="POST"
                                          action="rescuer_approvals.php"
                                          class="d-flex gap-2">

                                        <input type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                                        <input type="hidden"
                                            name="user_id"
                                            value="<?= (int)$row['id'] ?>">

                                        <button type="submit"
                                            name="action"
                                            value="approve"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirm('Approve this Rescuer account?');">
                                            Approve
                                        </button>

                                        <button type="submit"
                                            name="action"
                                            value="reject"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Reject this Rescuer account?');">
                                            Reject
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
