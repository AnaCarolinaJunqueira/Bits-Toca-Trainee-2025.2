<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fórum - Bit's Toca</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&family=Roboto:wght@400;700&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/public/css/forum.css">
    <link rel="stylesheet" href="/public/css/modals.css">
</head>

<body>
    <?php require 'app/views/site/navbar.view.php'; ?>

    <main>
        <div class="forum-header">
            <h1>Comunidade</h1>
            <p class="subtitle">Antes de começar uma nova thread, faça uma busca para descobrir se já temos alguma discussão aberta sobre o time que você procura.</p>

            <form action="/forum" method="GET" class="search-container">
                <input type="text" name="search" placeholder="O que você procura?" value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

       <div class="action-bar">
            <?php if(isset($_SESSION['user'])): ?>
                <p>Não achou o que procurava?</p>
                <button class="btn-new-thread" onclick="abrirModal('modal-nova-discussao')">NOVA DISCUSSÃO</button>
            <?php else: ?>
                <p>Faça login para criar uma nova discussão.</p>
                <a href="/login" class="btn-new-thread" style="text-decoration: none; display: inline-block;">LOGIN</a>
            <?php endif; ?>
        </div>

        <div class="forum-grid">
            <div class="thread-list-container">
                <div class="table-header">
                    <span>CATEGORIAS</span>
                    <span>TÍTULO</span>
                    <span>AUTOR</span>
                    <span>RESPOSTAS</span>
                </div>

                <?php if (empty($discussions)): ?>
                    <div class="thread-row empty-row">
                        <p>Nenhuma discussão encontrada.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($discussions as $d): ?>
                        <a href="/forum/discussion?id=<?= $d->ID ?>" class="thread-row">
                            <span class="col-cat"><?= htmlspecialchars($d->CATEGORIA ?? 'Geral') ?></span>
                            <span class="col-title"><?= htmlspecialchars($d->TITULO) ?></span>
                            <span class="col-author"><?= htmlspecialchars($d->AUTOR_NOME) ?></span>
                            <span class="col-replies"><?= $d->TOTAL_RESPOSTAS ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="foot">
                    <?php require 'app/views/site/components/pagination_forum.php'; ?>
                </div>
                </div>

            <div class="sidebar-categories">
                <h2>Categorias do fórum</h2>
                <div class="cat-divider"></div>

                <ul>
                    <?php
                    $categories = ['Analises', 'Previas', 'Noticias', 'Entrevistas', 'Devlogs', 'Opiniao', 'Eventos'];
                    foreach ($categories as $cat):
                        $count = 0;
                        foreach ($catStats as $stat) {
                            if ($stat->CATEGORIA == $cat) $count = $stat->total;
                        }
                    ?>
                        <li>
                            <a href="/forum?category=<?= $cat ?>">
                                <span><?= strtoupper($cat) ?></span>
                                <span><?= str_pad($count, 2, '0', STR_PAD_LEFT) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>

    <?php require 'app/views/site/modals/modal-nova-discussao.html'; ?>
    
    <?php require 'app/views/admin/modals/modal-goto-page.html'; ?>

    <?php require 'app/views/site/Footer.html'; ?>
    <script src="/public/js/modals-discussao.js"></script>
</body>

</html>