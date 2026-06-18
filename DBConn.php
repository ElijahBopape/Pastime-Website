<?php
/*
 * Pastimes Web Application - WEDE6021/w
 * Student: Elijah Bopape | Student Number: ST10445847
 * Declaration: This code is my own work except where referenced in the POE documentation.
 */

class DatabaseConnection
{
    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = '';
    private string $database = 'ClothingStore';
    private mysqli $connection;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->connection = new mysqli($this->host, $this->username, $this->password);
        $this->connection->query("CREATE DATABASE IF NOT EXISTS `{$this->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $this->connection->select_db($this->database);
        $this->connection->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}

$databaseObject = new DatabaseConnection();
$woodDb = $databaseObject->getConnection();

function cleanInput(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function formatRand($amount): string
{
    return 'R' . number_format((float)$amount, 2);
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireRole(string $role): void
{
    requireLogin();
    if (($_SESSION['role'] ?? '') !== $role) {
        header('Location: dashboard.php');
        exit();
    }
}

function requireAdmin(): void
{
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: admin_login.php');
        exit();
    }
}

function currentUser(mysqli $woodDb): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $stmt = $woodDb->prepare('SELECT * FROM tblUser WHERE user_id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: null;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function showFlash(): void
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        echo '<div class="alert alert-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
        unset($_SESSION['flash']);
    }
}

function productImage(string $image): string
{
    if (str_starts_with($image, 'uploads/')) {
        return $image;
    }
    return 'assets/' . $image;
}
?>
