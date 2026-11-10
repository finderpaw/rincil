
<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$pet_id = $_POST['pet_id'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['error'] = "Please enter your username and password.";
    header("Location: login.php");
    exit();
}

$sql = "SELECT id, username, email, password
        FROM users
        WHERE username = ? OR email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;

        // ADMIN LOGIN
        if (strtolower($user['username']) === 'admin') {
            $_SESSION['role'] = 'admin';

            header("Location: admin/admin.php");
            exit();
        }

        // OTHER USERS
        $_SESSION['role'] = 'user';

        if (!empty($pet_id)) {
            header("Location: pet_details.php?id=" . urlencode($pet_id));
        } else {
            header("Location: homepage.php");
        }
        exit();

    } else {
        $_SESSION['error'] = "Incorrect password.";
    }

} else {
    $_SESSION['error'] = "Account not found.";
}

$stmt->close();

header("Location: login.php");
exit();
?>
2