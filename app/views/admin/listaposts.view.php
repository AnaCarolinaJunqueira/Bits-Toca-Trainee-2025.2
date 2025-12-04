<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bits toca - Admin Posts</title>

    <link rel="stylesheet" href="/public/css/listaposts.css">
    <link rel="stylesheet" href="/public/css/modals.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <style>
        body { display: flex; overflow-x: hidden; }
        main { flex: 1; width: 100%; }
        /* Ajuste responsivo */
        @media (max-width: 768px) { body { display: block; } }
    </style>
</head>

<body>
    <?php require 'app/views/admin/sidebar.html'; ?>

    <main>
        <div class="container">
            <div class="cabeçalho">
                <div class="logo">
                    <div id="logo">
                        <img src="/public/assets/Logo Blog.png" alt="Logo Bits Toca Branca">
                    </div>
                </div>
                <div class="titulo">
                    <h1>Tabela de Posts</h1>
                </div>
                <div class="espaço"> </div>
            </div>
            
            <div class="botoes">
                <form method="GET" action="/admin/listaposts" class="barra-pesquisa">
                    <img src="/public/assets/icon_pesquisa.png" alt="icone de pesquisa">
                    <input type="text" id="pesquisa" name="search" placeholder="Pesquisar..." value="<?= htmlspecialchars($search_term ?? '') ?>">
                    <button type="submit" style="display: none;"></button>
                </form>
                <div class="botao-post">
                    <p> NOVO POST</p>
                    <i class="bi bi-plus-circle"></i>
                </div>
            </div>

            <div class="container-tabela">
                <div class="tabela">
                    <table>
                        <thead>
                            <tr class="header">
                                <th class="id-column-header"><p>ID</p></th>
                                <th class="title-column-header"><p>TÍTULO</p></th>
                                <th class="author-column-header"><p>AUTOR</p></th>
                                <th class="date"><p>DATA DE CRIAÇÃO</p></th>
                                <th class="action"><p>AÇÕES</p></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post) : ?>
                                <tr>
                                    <td class="id-column"><p><?= $post->ID; ?></p></td>
                                    <td class="title-column"><p><?= htmlspecialchars($post->TITULO); ?></p></td>
                                    <td class="author-column"><p><?= htmlspecialchars($post->AUTOR_NOME); ?></p></td>
                                    <td class="data"><p><?= date('d/m/Y', strtotime($post->DATA_POSTAGEM)); ?></p></td>
                                    <td class="açoes">
                                        <div>
                                            <div class="button-content">
                                                <button class="btn-modal btn-view"
                                                    data-id="<?= $post->ID; ?>"
                                                    data-titulo="<?= htmlspecialchars($post->TITULO); ?>"
                                                    data-conteudo="<?= htmlspecialchars($post->CONTEUDO); ?>"
                                                    data-imagem="<?= $post->IMAGEM; ?>"
                                                    data-imagem_recent="<?= $post->IMAGEM_RECENT ?? ''; ?>"
                                                    data-autor_nome="<?= htmlspecialchars($post->AUTOR_NOME); ?>"
                                                    data-data="<?= date('Y-m-d', strtotime($post->DATA_POSTAGEM)); ?>"
                                                    data-categoria="<?= htmlspecialchars($post->CATEGORIA ?? ''); ?>"
                                                    data-rating="<?= $post->AVALIACAO; ?>"
                                                    data-data_edicao="<?= $post->DATA_EDICAO ? date('d/m/Y, H:i:s', strtotime($post->DATA_EDICAO)) : ''; ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="button-content">
                                                <button class="btn-modal btn-edit"
                                                    data-id="<?= $post->ID; ?>"
                                                    data-titulo="<?= htmlspecialchars($post->TITULO); ?>"
                                                    data-conteudo="<?= htmlspecialchars($post->CONTEUDO); ?>"
                                                    data-imagem="<?= $post->IMAGEM; ?>"
                                                    data-imagem_recent="<?= $post->IMAGEM_RECENT ?? ''; ?>"
                                                    data-autor_nome="<?= htmlspecialchars($post->AUTOR_NOME); ?>"
                                                    data-data="<?= date('Y-m-d', strtotime($post->DATA_POSTAGEM)); ?>"
                                                    data-categoria="<?= htmlspecialchars($post->CATEGORIA ?? ''); ?>"
                                                    data-rating="<?= $post->AVALIACAO; ?>"
                                                    data-data_edicao="<?= $post->DATA_EDICAO ? date('d/m/Y, H:i:s', strtotime($post->DATA_EDICAO)) : ''; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                            <div class="button-content">
                                                <button class="btn-modal btn-delete" data-id="<?= $post->ID; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php $figs = range(1, 50); $random_keys = array_rand($figs, 3); ?>
                <img src="/public/assets/figurinhas/<?= $figs[$random_keys[0]] ?>.png" class="figurinha" id="fig1" alt="figurinha 1">
                <img src="/public/assets/figurinhas/<?= $figs[$random_keys[1]] ?>.png" class="figurinha" id="fig2" alt="figurinha 2">
                <img src="/public/assets/figurinhas/<?= $figs[$random_keys[2]] ?>.png" class="figurinha" id="fig3" alt="figurinha 3">

                <?php require 'app/views/admin/components/paginacao.php'; ?>
            </div>
        </div>
    </main>

    <?php require 'app/views/admin/modals/modal-novo-post.html'; ?>
    <?php require 'app/views/admin/modals/modal-editar-post.html'; ?>
    <?php require 'app/views/admin/modals/modal-deletar-post.html'; ?>
    <?php require 'app/views/admin/modals/modal-visualizar-post.html'; ?>
    <?php require 'app/views/admin/modals/modal-goto-page.html'; ?>
    
    <script src="/public/js/listaposts.js"></script>
    <script src="/public/js/modals.js"></script>
</body>
</html>