<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: /login');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../../public/css/dashboard_style.css">

    <title>Dashboard</title>
</head>

<body>
    <div class="dashboard-overlay">
        <div class="dashboard-header">
            <form action="/logout" method="POST">
                <button type="submit" class="logout-button"><i class="bi bi-arrow-left-square"></i></button>
            </form>
            <h1>DASHBOARD</h1>
            <div class="dashboard-logo">
                <img src="../../../public/assets/logo-site.png" alt="Logo Bits Toca">
            </div>
        </div>
        <div class="dashboard-content">
            <form action="" class="dashboard-buttons">
                <button class="dashboard-button-pp">PÁGINA DE PUBLICAÇÕES
                    <i class="bi bi-menu-button-wide button-icon-pp"></i>
                </button>
            </form>
            <form action="" class="dashboard-buttons">
                <button class="dashboard-button-pu">PÁGINA DE USUÁRIOS
                    <i class="bi bi-person-badge button-icon-pu"></i>
                </button>
            </form>
        </div>
    </div>
    <script src="../../../public/js/dashboard.js"></script>
</body>

</html>