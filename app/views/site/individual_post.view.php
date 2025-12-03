<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($individual_post->TITULO) ?> - Bit's Toca</title>
    <link rel="stylesheet" href="../../../public/css/individual_post.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=VT323">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Press+Start+2P'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap">
</head>

<body>
    <?php require 'app/views/site/navbar.view.php'; ?>

    <main>
        <div class="content">
            <a href="/" class="button-back"><i class="bi bi-arrow-left-short"></i></a>
            <h1><?= htmlspecialchars($individual_post->TITULO) ?></h1>
            <div class="post-info">
                <div class="user">
                    <img src="/public/assets/Imagem-user.jpg" alt="icone do usuario">
                    <p>@<?= htmlspecialchars($author_post->NOME); ?></p>
                </div>
                <div class="date"><?php
                $dataHora  = explode(" ", htmlspecialchars($individual_post->DATA_POSTAGEM));
                $data = explode("-", $dataHora[0]);                 
                ?>
                <p><?= $data[2] . '/' . $data[1] . '/' . $data[0]; ?></p>
                <p><?= $dataHora[1]; ?></p>
                </div>
            </div>
            <img class="post-image" src="/public/<?= htmlspecialchars($individual_post->IMAGEM) ?>" alt="imagem do post">
            <div class="post-description">
                <?php
                $conteudo = htmlspecialchars($individual_post->CONTEUDO);
                $conteudo = preg_replace('/\n\s*\n/', "</p><p>", $conteudo);
                ?>
                <p><?= $conteudo ?></p>
            </div>
            <div class="rating-content">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/like?post_id=<?= htmlspecialchars($individual_post->ID)?>&user_id=<?= htmlspecialchars($_SESSION['user']->ID) ?>"
                    class="likes-content <?php if (htmlspecialchars($is_like)): ?>active<?php endif; ?>">
                <?php else: ?>
                    <a href="/like" class="likes-content">
                <?php endif; ?>
                    <div class="likes-icon">
                        <i class="bi bi-heart<?php if (htmlspecialchars($is_like)): ?>-fill<?php endif; ?>"></i>
                    </div>
                    <div class="likes-text">
                        <p><?= htmlspecialchars($total_likes) ?></p>
                        <p>curtidas</p>
                    </div>
                </a>
                <div class="stars-content">
                    <div class="stars-text">
                        <p>Nota</p>
                        <p>do</p>
                        <p>autor:</p>
                    </div>

                    <div class="stars-icons">
                        <?php $rating = $individual_post->AVALIACAO;
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
        </div>
    </main>
</body>

<?php require 'app/views/site/Footer.html'; ?>

<!-- <script src="../../../public/js/individual_post.js"></script> -->

</html>