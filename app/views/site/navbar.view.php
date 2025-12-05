<link rel="stylesheet" href="/public/css/navbar.css">
<link rel="stylesheet" href="/public/css/modals.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap">
<header>
    <nav class="navbar">
        <div class="logo">
            <a href="/"><img src="/public/assets/logo-site.png" alt="Logo Bit's Toca Clara"></a>
        </div>
        <ul class="menu-itens">
            <li class="nav-item">
                <a href="/" class="nav-link"><i class="bi bi-house"></i><div><p>HOME</p></div></a>
            </li>
            <li class="nav-item">
                <a href="/" class="nav-link"><i class="bi bi-pencil-square"></i><div><p>POSTS</p></div></a>
            </li>
            <li class="nav-item">
                <a href="/forum" class="nav-link"><i class="bi bi-person-vcard"></i><div><p>FÓRUM</p></div></a>
            </li>

            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link"><i class="bi bi-columns-gap"></i><div><p>DASHBOARD</p></div></a>
                </li>
                <li class="nav-item dropdown-container">
                    <div class="nav-link-user" id="user-dropdown-btn">
                        <img src="/public/<?= htmlspecialchars($_SESSION['user']->AVATAR) ?>" alt="User Avatar" class="avatar-img">
                        <span class="user-name-display"><?= htmlspecialchars(explode(' ', $_SESSION['user']->NOME)[0]) ?></span>
                        <i class="bi bi-caret-down-fill" style="font-size: 0.8rem; margin-left: 5px; color: white;"></i>
                    </div>
                    
                    <div class="dropdown-menu" id="user-dropdown-menu">
                        <a href="#" class="dropdown-item" id="btn-edit-profile-nav"
                           data-id="<?= $_SESSION['user']->ID ?>"
                           data-nome="<?= htmlspecialchars($_SESSION['user']->NOME) ?>"
                           data-email="<?= htmlspecialchars($_SESSION['user']->EMAIL) ?>"
                           data-avatar="<?= htmlspecialchars($_SESSION['user']->AVATAR) ?>"
                            <i class="bi bi-person-gear"></i> Editar Perfil
                        </a>
                        <a href="/logout" class="dropdown-item logout-item">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a href="/login" class="nav-link"><i class="bi bi-person"></i><div><p>LOGIN</p></div></a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="menu-hamburger">
            <i class="bi bi-list" id="menu-icone"></i>
        </div>
    </nav>
</header>
<script src="/public/js/navbar.js"></script>