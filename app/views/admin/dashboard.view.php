<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/dashboard_style.css">

    <title>Dashboard</title>
</head>
<body>
    <main>
        <div class="dashboard_overlay">
            <div class="dashboard_header">
                <a href="/" class="logout-button"><img src="/public/assets/home_button.png"></a>
                <h1>DASHBOARD</h1>
                <img src="/public/assets/logo.png" alt="Logo" class="dashboard_logo">
            </div>
            <div class="dashboard_content">
                <a href="/admin/listaposts">
                    <button class="dashboard-button-pp">PÁGINA DE PUBLICAÇÕES
                        <span class="button-icon-pp"><img src="/public/assets/PPL.png"></span>
                    </button>
                </a>
                <a href="/admin/listausuarios">
                    <?php if(isset($_SESSION['user']) && ($_SESSION['user']->IS_ADMIN)): ?>
                    <button class="dashboard-button-pu">PÁGINA DE USUÁRIOS
                        <span class="button-icon-pu"><img src="/public/assets/PUL.png"></span>
                    </button>
                    <?php else: ?>
                    <?php endif; ?>
                </a>
            </div>

        </div>
    </main>
    </body>
</html>