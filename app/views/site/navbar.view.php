<link rel="stylesheet" href="/public/css/navbar.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap">
<header>
    <nav class="navbar">
        <div class="logo">
            <a href="/"><img src="/public/assets/logo-site.png" alt="Logo Bit's Toca Clara"></a>
        </div>
        <ul class="menu-itens">
            <li class="nav-item">
                <a href="/" class="nav-link"><i class="bi bi-house"></i>
                    <div>
                        <p>HOME</p>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="/" class="nav-link"><i class="bi bi-pencil-square"></i>
                    <div>
                        <p>POSTS</p>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a href="/forum" class="nav-link"><i class="bi bi-person-vcard"></i>
                    <div>
                        <p>FÓRUM</p>
                    </div>
                </a>
            </li>

            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link"><i class="bi bi-columns-gap"></i>
                        <div>
                            <p>DASHBOARD</p>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/logout" class="nav-link nav-link-user">
                        <img src="/public/assets/images/1763943169_6923a301eb2fd_Avatar2.jpeg" alt="User Avatar" class="avatar-img">
                        <div class="logout-content">
                            <i class="bi bi-box-arrow-right"></i>
                            <p>SAIR</p>
                        </div>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a href="/login" class="nav-link"><i class="bi bi-person"></i>
                        <div>
                            <p>LOGIN</p>
                        </div>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
        <div class="menu-hamburger">
            <i class="bi bi-list" id="menu-icone"></i>
        </div>
    </nav>
</header>
<script src="/public/js/navbar.js"></script>