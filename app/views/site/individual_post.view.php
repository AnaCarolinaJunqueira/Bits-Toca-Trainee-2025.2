<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post->TITULO) ?></title>
    <link rel="stylesheet" href="../../../public/css/individual_post.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=VT323">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Press+Start+2P'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap">
    <link rel="stylesheet" href="/public/css/modals.css">
</head>

<body>
    <?php require 'app/views/site/navbar.view.php'; ?>
    <main>
        <div class="content">
            <a href="/posts" class="button-back"><i class="bi bi-arrow-left-short"></i></a>
            <h1><?= htmlspecialchars($post->TITULO) ?></h1>
            <div class="post-info">
                <div class="user">
                    <img src="/public/<?= htmlspecialchars($author_post->AVATAR ?? 'assets/avatars/default.png') ?>" alt="icone do usuario">
                    <p>@<?= htmlspecialchars($author_post->NOME); ?></p>
                </div>
                <!-- <?php if ($post->DATA_EDICAO): ?>
                    <p class="date" style="margin-left: auto; margin-right: 1rem;">(Editado)</p>
                <?php endif; ?> -->
                <div class="date">
                    <p><?= date('d/m/Y', strtotime($post->DATA_POSTAGEM)) ?></p>
                    <p><?= date('H:i:s', strtotime($post->DATA_POSTAGEM)) ?></p>
                </div>
            </div>
            <?php if ($post->IMAGEM): ?>
                <img class="post-image" src="/public/<?= htmlspecialchars($post->IMAGEM) ?>" alt="imagem do post">
            <?php endif; ?>
            <div class="post-description">
                <?php
                $conteudo = htmlspecialchars($post->CONTEUDO);
                $conteudo = preg_replace('/\n\s*\n/', "</p><p>", $conteudo);
                ?>
                <p><?= $conteudo ?></p>
            </div>
            <div class="rating-content">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/post/like?post_id=<?= htmlspecialchars($post->ID) ?>"
                        class="likes-content <?php if (htmlspecialchars($is_like)): ?>active<?php endif; ?>">
                    <?php else: ?>
                        <a href="/post/like" class="likes-content">
                        <?php endif; ?>
                        <div class="likes-icon">
                            <i class="bi bi-heart<?php if (htmlspecialchars($is_like)): ?>-fill<?php endif; ?>"></i>
                        </div>
                        <div class="likes-text">
                            <p><?= htmlspecialchars($total_likes) ?></p>
                            <p>curtida<?php if (htmlspecialchars($total_likes) != 1): ?>s<?php endif; ?></p>
                        </div>
                        </a>
                        <div class="stars-content">
                            <div class="stars-text">
                                <p>Nota</p>
                                <p>do</p>
                                <p>autor:</p>
                            </div>
                            <div class="stars-icons">
                                <?php
                                $rating = (int)$post->AVALIACAO;
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $rating): ?>
                                        <i class="bi bi-star-fill star-fill"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star-fill star-empty"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
            </div>
            <div class="comments-section">
                <h3>Comentários</h3>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <div class="user-info">
                                    <img src="/public/<?= htmlspecialchars($comment->AVATAR ?? 'assets/avatars/default.png') ?>" alt="Avatar">
                                    <span class="comment-author">@<?= htmlspecialchars($comment->AUTOR_NOME) ?></span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <?php if (isset($_SESSION['user']) && ($_SESSION['user']->ID == $comment->USER_ID || $_SESSION['user']->IS_ADMIN)): ?>
                                        <button class="btn-icon btn-edit-comment"
                                            data-id="<?= $comment->ID ?>"
                                            data-post-id="<?= $post->ID ?>"
                                            data-conteudo="<?= htmlspecialchars($comment->CONTEUDO) ?>">
                                            <i class="bi bi-pencil-fill" style="color: #55768C;"></i>
                                        </button>
                                        <button class="btn-icon btn-delete-comment"
                                            data-id="<?= $comment->ID ?>"
                                            data-post-id="<?= $post->ID ?>">
                                            <i class="bi bi-trash-fill" style="color: #B83556;"></i>
                                        </button>
                                    <?php endif; ?>

                                    <span class="comment-date"><?= date('d/m/Y H:i', strtotime($comment->DATA_CRIACAO)) ?></span>
                                </div>
                            </div>
                            <div class="comment-body">
                                <p><?= nl2br(htmlspecialchars($comment->CONTEUDO)) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-comments">Seja o primeiro a comentar!</p>
                <?php endif; ?>

                <div class="new-comment-section">
                    <?php if (isset($_SESSION['user'])): ?>
                        <p>Deixe seu comentário</p>
                        <div class="new-comment-box">
                            <div class="current-user-avatar">
                                <img src="/public/<?= htmlspecialchars($_SESSION['user']->AVATAR ?? 'assets/images/default.png') ?>" alt="User">
                            </div>
                            <form action="/post/comment/store" method="POST" class="new-comment-form">
                                <input type="hidden" name="post_id" value="<?= $post->ID ?>">
                                <textarea name="conteudo" placeholder="O que você achou?" required></textarea>
                                <button type="submit"><i class="bi bi-send-fill"></i></button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-to-comment">
                            <p>Faça login para comentar</p>
                            <a href="/login">LOGIN</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php require 'app/views/site/modals/modal-editar-comentario.html'; ?>
    <?php require 'app/views/site/modals/modal-deletar-comentario.html'; ?>

    <?php require 'app/views/site/Footer.html'; ?>
</body>
<script src="/public/js/individual_post.js"></script>
<script src="/public/js/modals.js"></script>
</html>