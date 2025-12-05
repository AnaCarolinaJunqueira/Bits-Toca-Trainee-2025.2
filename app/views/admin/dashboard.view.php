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
    <main>
        <div class="dashboard-overlay">
            <div class="dashboard-header">
                <a href="/" class="dashboard-back">
                    <button class="back-button"><i class="bi bi-arrow-left-square"></i></button>
                </a>
                <h1>DASHBOARD</h1>
                <div href="/" class="dashboard-logo">
                    <img src="../../../public/assets/logo-site.png" alt="Logo Bits Toca">
                </div>
            </div>
            <div class="dashboard-content">
                <a href="/admin/listaposts" class="dashboard-buttons">
                    <button class="dashboard-button-pp"><p>PÁGINA DE PUBLICAÇÕES</p>
                        <i class="bi bi-menu-button-wide button-icon-pp"></i>
                    </button>
                </a>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']->IS_ADMIN): ?>
                    <a href="/admin/listausuarios" class="dashboard-buttons">
                        <button class="dashboard-button-pu"><p>PÁGINA DE USUÁRIOS</p>
                            <i class="bi bi-person-badge button-icon-pu"></i>
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>