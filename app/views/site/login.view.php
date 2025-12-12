<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bit's Toca</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../../public/css/loginpage.css">
</head>

<body>
    <div class="login-overlay">
        <div class="login-header">
            <div class="button-back">
                <a href="/"><i class="bi bi-arrow-left-square"></i></a>
            </div>
            <div class="LogoBitsToca">
                <img src="../../../public/assets/logo-site.png" alt="Logo Bits Toca">
            </div>
            <div class="space"></div>
        </div>
        <h2>LOGIN</h2>
        <div class="mensagem">
            <p>
                <?php
                if (isset($_SESSION['mensagem'])) {
                    echo $_SESSION['mensagem'];
                    unset($_SESSION['mensagem']);
                }
                ?>
            </p>
        </div>
        <form action="/login" method="POST">
            <div class="input-content">
                <input type="text" class="email" name="email" autocomplete="off" placeholder="Digite seu email" required>
            </div>
            <div class="input-content">
                <input type="password" class="senha" name="senha" autocomplete="off" placeholder="Digite sua senha" required>
                <i class="fa-solid fa-eye-slash" id="togglePassword"></i>
            </div>
            <div class="button-content">
                <button class="login-button" type="submit">LOGIN</button>
            </div>
        </form>
    </div>
</body>
<script src="public/js/loginpage.js"></script>
</html>