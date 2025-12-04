<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bit's Toca</title>
    <link href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/public/css/loginpage.css">
</head>

<body>
    <div class="tamanho-body">
        <div class="login-overlay">
            <div class="button">
                <a href="/">
                    <img src="/public/assets/Home Button.png" alt="button">
                </a>
            </div>
            <div class="LogoBitsToca">
                <img src="/public/assets/Logo.png" alt="Logo Bits Toca Branca">
            </div>
            <h2>LOGIN</h2>
            
            <?php if(isset($error)): ?>
                <p style="color: #B83556; font-family: 'VT323', monospace; font-size: 1.5rem; margin-bottom: 10px;">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form action="/login" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center; gap: 20px;">
                <input type="email" name="email" id="check" placeholder="Digite seu email" required>
                
                <div class="password" id="check" style="width: 60%;">
                    <input type="password" name="password" id="check-password" placeholder="Digite sua senha" style="width: 85%;" required>
                    <i class="fa-solid fa-eye-slash" id="togglePassword"></i>
                </div>
                
                <button type="submit" class="botao-loginpage">LOGIN</button>
            </form>

        </div>
        <script src="/public/js/loginpage.js"></script>
    </div>
</body>

</html>