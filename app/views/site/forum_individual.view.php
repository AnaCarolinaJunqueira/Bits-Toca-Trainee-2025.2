<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($discussion->TITULO) ?> - Bit's Toca</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=VT323&family=Roboto:wght@400;700&family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/public/css/forum_individual.css">
    <link rel="stylesheet" href="/public/css/modals.css">
</head>

<body>
    <?php require 'app/views/site/navbar.view.php'; ?>

    <main>
        <div class="top-bar">
            <a href="/forum" class="btn-back" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-arrow-left"></i>
                <span style="font-family: 'VT323', monospace; font-size: 1.5rem;">VOLTAR</span>
            </a>
        </div>

        <div class="thread-container">
            <div class="post-card original-post">
                <div class="post-header">
                    <div class="user-info">
                        <img src="/public/<?= htmlspecialchars($discussion->AVATAR ?? 'assets/images/default.png') ?>" alt="Avatar">
                        <span class="username">@<?= htmlspecialchars($discussion->AUTOR_NOME) ?></span>
                    </div>

                    <div class="discussion-actions" style="display: flex; align-items: center; gap: 15px;">
                        <span class="timestamp"><?= date('d/m/Y H:i', strtotime($discussion->DATA_POSTAGEM)) ?></span>
                        <?php
                        if (isset($_SESSION['user']) && (
                            ($_SESSION['user']->ID == $discussion->AUTOR_ID) ||
                            (!empty($_SESSION['user']->IS_ADMIN))
                        )):
                        ?>
                            <button class="btn-edit-discussion"
                                data-id="<?= $discussion->ID ?>"
                                data-titulo="<?= htmlspecialchars($discussion->TITULO) ?>"
                                data-conteudo="<?= htmlspecialchars($discussion->CONTEUDO) ?>"
                                data-categoria="<?= htmlspecialchars($discussion->CATEGORIA) ?>"
                                data-imagem="<?= htmlspecialchars($discussion->IMAGEM ?? '') ?>"
                                style="all: unset; color: #55768C; cursor: pointer; font-size: 1.2rem;"
                                title="Editar discussão">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button class="btn-delete-discussion-modal"
                                data-id="<?= $discussion->ID ?>"
                                style="all: unset; color: #B83556; cursor: pointer; font-size: 1.2rem;"
                                title="Deletar discussão">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="post-content">
                    <h2><?= htmlspecialchars($discussion->TITULO) ?></h2>
                    <p><?= nl2br(htmlspecialchars($discussion->CONTEUDO)) ?></p>
                    <?php if ($discussion->IMAGEM): ?>
                        <img src="/public/<?= $discussion->IMAGEM ?>" class="attachment-img">
                    <?php endif; ?>
                </div>
            </div>

            <div class="divider-line"></div>

            <?php foreach ($replies as $reply): ?>
                <div class="post-card reply-post">
                    <div class="post-header">
                        <div class="user-info">
                            <img src="/public/<?= htmlspecialchars($reply->AVATAR ?? 'assets/images/default.png') ?>" alt="Avatar">
                            <span class="username">@<?= htmlspecialchars($reply->AUTOR_NOME) ?></span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span class="timestamp"><?= date('d/m/Y H:i', strtotime($reply->DATA_CRIACAO)) ?></span>

                            <?php
                            if (isset($_SESSION['user']) && (
                                ($_SESSION['user']->ID == $reply->USER_ID) ||
                                (!empty($_SESSION['user']->IS_ADMIN))
                            )):
                            ?>
                                <button class="btn-delete-reply-modal"
                                    data-id="<?= $reply->ID ?>"
                                    style="background: none; border: none; color: #B83556; cursor: pointer; font-size: 1.2rem;"
                                    title="Deletar resposta">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="post-content">
                        <p><?= nl2br(htmlspecialchars($reply->CONTEUDO)) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="reply-area">
                <?php if (isset($_SESSION['user'])): ?>
                    <p>RESPONDER</p>
                    <div class="reply-box">
                        <div class="current-user-avatar">
                            <img src="/public/<?= htmlspecialchars($_SESSION['user']->AVATAR ?? 'assets/images/default.png') ?>" alt="User">
                        </div>
                        <form action="/forum/reply/store" method="POST" class="reply-form">
                            <input type="hidden" name="discussion_id" value="<?= $discussion->ID ?>">
                            <textarea name="conteudo" placeholder="ESCREVA SUA RESPOSTA" required></textarea>
                            <button type="submit"><i class="bi bi-send-fill"></i></button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt" style="text-align: center; padding: 20px;">
                        <p style="font-family: 'VT323', monospace; font-size: 1.5rem; margin-bottom: 15px;">Faça login para responder a essa discussão.</p>
                        <a href="/login" style="
                            text-decoration: none; 
                            display: inline-block; 
                            background-color: #B83556; 
                            color: white; 
                            padding: 10px 30px; 
                            border-radius: 15px; 
                            font-family: 'Roboto', sans-serif; 
                            font-weight: bold;
                            box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            LOGIN
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php require 'app/views/site/Footer.html'; ?>

    <?php require 'app/views/site/modals/modal-nova-discussao.html'; ?>
    <?php require 'app/views/site/modals/modal-editar-discussao.html'; ?>

    <?php require 'app/views/site/modals/modal-deletar-resposta.html'; ?>
    <?php require 'app/views/site/modals/modal-deletar-discussao.html'; ?>

    <script src="/public/js/modals-discussao.js"></script>
</body>

</html>