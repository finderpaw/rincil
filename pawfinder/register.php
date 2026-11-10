
<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $contact = trim($_POST["contact"] ?? "");
    $role = trim($_POST["role"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Required fields
    if (
        $fullname === "" || $email === "" ||
        $address === "" || $contact === "" ||
        $role === "" || $username === "" ||
        $password === ""
    ) {
        $_SESSION['error'] = "Please complete all fields.";
        header("Location: register.php");
        exit();
    }

    // Only these roles can self-register
    $allowedRoles = ["Local", "Visitor", "Rescuer"];

    if (!in_array($role, $allowedRoles, true)) {
        $_SESSION['error'] = "Invalid account role.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: register.php");
        exit();
    }

    if (!preg_match("/^09\d{9}$/", $contact)) {
        $_SESSION['error'] = "Enter a valid 11-digit Philippine contact number.";
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters.";
        header("Location: register.php");
        exit();
    }

    if (
        strlen($fullname) > 100 ||
        strlen($username) > 50 ||
        strlen($address) > 255 ||
        strlen($contact) > 20
    ) {
        $_SESSION['error'] = "One or more fields are too long.";
        header("Location: register.php");
        exit();
    }

    // Check duplicate username or email
    $check = $conn->prepare(
        "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1"
    );

    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        $_SESSION['error'] = "Username or email already exists.";
        header("Location: register.php");
        exit();
    }

    $check->close();

    // Secure password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Rescuers need admin approval
    $status = ($role === "Rescuer") ? "Pending" : "Approved";

    $sql = "INSERT INTO users
        (fullname, email, address, contact, role, username, password, account_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log($conn->error);
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }

    $stmt->bind_param(
        "ssssssss",
        $fullname,
        $email,
        $address,
        $contact,
        $role,
        $username,
        $hashedPassword,
        $status
    );

    if ($stmt->execute()) {

        $stmt->close();
        $conn->close();

        if ($status === "Pending") {
            $_SESSION['success'] =
                "Registration successful! Your Rescuer account is waiting for admin approval.";
        } else {
            $_SESSION['success'] =
                "Account created successfully! You can now log in.";
        }

        header("Location: login.php");
        exit();

    } else {
        if ($stmt->errno == 1062) {
            $_SESSION['error'] = "Username or email already exists.";
        } else {
            error_log($stmt->error);
            $_SESSION['error'] = "Registration failed. Please try again.";
        }

        $stmt->close();
        header("Location: register.php");
        exit();
    }
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pawfinder - Account Registration</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

<div class="register-card">

    <div class="sidebar-left">
        <div>
            <div class="brand-logo">🐾 PAWFINDER</div>

            <ul class="step-list">
                <li class="step-item active">○ Account Setup</li>
                <li class="step-item">○ Resident Details</li>
                <li class="step-item">○ Pet Preferences</li>
                <li class="step-item">○ Confirm</li>
            </ul>
        </div>

        <div>
            <div class="sidebar-img-container">
                <img src="image/animal.jpg" alt="Animal">
            </div>

            <div class="back-to-login">
                <a href="homepage.php">← Back to Homepage</a>
            </div>
        </div>
    </div>

    <div class="form-right">

        <h2>Welcome to Pawfinder!</h2>
        <p>Please provide your basic information to get started.</p>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname"
                            maxlength="100"
                            placeholder="Juan Dela Cruz" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email"
                            maxlength="100"
                            placeholder="juan@gmail.com" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Address (Mabalacat City)</label>
                        <input type="text" name="address"
                            maxlength="255"
                            placeholder="e.g., Dau, Mabalacat City" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" name="contact"
                            pattern="09[0-9]{9}"
                            maxlength="11"
                            placeholder="09123456789" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Register As</label>

                <div class="radio-group">
                    <label>
                        <input type="radio" name="role"
                            value="Local" required>
                        Local Resident
                    </label>

                    <label>
                        <input type="radio" name="role"
                            value="Visitor">
                        Visitor
                    </label>

                    <label>
                        <input type="radio" name="role"
                            value="Rescuer">
                        Pet Rescuer / Volunteer
                    </label>
                </div>

                <small>
                    Rescuer accounts require admin approval.
                </small>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username"
                            maxlength="50"
                            placeholder="Username" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password"
                            minlength="8"
                            placeholder="At least 8 characters" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Create Account
            </button>

        </form>
    </div>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    const password = document.querySelector('[name="password"]').value;

    if (password.length < 8) {
        event.preventDefault();
        alert("Password must be at least 8 characters long!");
    }
});
</script>

</body>
</html>
