<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="/public/css/post_page.css">
    <link rel="stylesheet" href="/public/css/modals.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=VT323&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bit's Toca - Posts</title>
</head>

<body>
    <?php require 'app/views/site/navbar.view.php'; ?>

    <div id="título-pagina-post">
        <h1>PÔSTERES</h1>
        <h1>RECENTES</h1>
    </div>

    <div class="container-filtro-pesquisa">
    <div id="filtro-pesquisa">
        <div id="filtro">
            <div class="form-group" style="margin-bottom: 0; min-width: 220px;">
                <div class="custom-select">
                    <select id="category-filter">
                        <option value="/posts" <?= empty($currentCategory) ? 'selected' : '' ?>>Todas as categorias</option>
                        
                        <?php 
                        $cats = [
                            'Analises' => 'Análises',
                            'Previas' => 'Prévias',
                            'Noticias' => 'Notícias',
                            'Entrevistas' => 'Entrevistas',
                            'Devlogs' => 'Devlogs',
                            'Opiniao' => 'Opinião',
                            'Eventos' => 'Eventos'
                        ];
                        $currentCat = $currentCategory ?? '';
                        ?>

                        <?php foreach($cats as $key => $label): ?>
                            <option value="?category=<?= $key ?>" <?= $currentCat == $key ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <form id="barra-pesquisa" action="/posts" method="GET">
            <input type="text" name="search" value="<?= htmlspecialchars($searchTerm ?? '') ?>" placeholder="Pesquisar...">
            
            <?php if(!empty($currentCategory)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory) ?>">
            <?php endif; ?>

            <button type="submit" style="background: none; border: none; cursor: pointer;">
                <img id="lupa-icon" src="/public/assets/استعمليه ماوس💖💖-Photoroom 1.png" alt="Search">
            </button>
            <img id="fliperama-icon" src="/public/assets/download (3)-Photoroom 1.png" alt="Arcade">
        </form>
    </div>
    </div>

    <div id="posteres">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <a href="/post?id=<?= $post->ID ?>" style="text-decoration: none;">
                    <div class="card">
                        <div id="heron-section">
                            <img src="/public/<?= htmlspecialchars($post->IMAGEM_RECENT ?? 'assets/images/default_recents.png') ?>" alt="<?= htmlspecialchars($post->TITULO) ?>">
                        </div>
                        <div class="texto-card">
                            <h2 id="titulo-card"><?= htmlspecialchars($post->TITULO) ?></h2>
                            <p id="texto-card">
                                <?= htmlspecialchars(mb_strimwidth($post->CONTEUDO, 0, 100, "...")) ?>
                            </p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: white; font-family: 'VT323', monospace; font-size: 2rem;">Nenhum post encontrado.</p>
        <?php endif; ?>
    </div>

    <div id="paginação" style="width: 100%; display: flex; justify-content: center;">
        <?php require 'app/views/site/components/pagination_forum.php'; ?>
    </div>

    <?php require 'app/views/admin/modals/modal-goto-page.html'; ?>
    <?php require 'app/views/site/Footer.html'; ?>
    
    <script src="/public/js/modals.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const filterSelect = document.getElementById('category-filter');
                if(!filterSelect) return;
                
                const wrapper = filterSelect.closest('.custom-select');
                if(!wrapper) return;

                const options = wrapper.querySelectorAll('.select-options div');
                
                options.forEach(opt => {
                    opt.addEventListener('click', () => {
                        const value = opt.dataset.value;
                        if(value) {
                            window.location.href = value;
                        }
                    });
                });
            }, 100);
        });
    </script>
</body>
</html>