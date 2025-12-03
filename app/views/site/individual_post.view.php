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
        <?php require 'app/views/site/navbar.html'; ?>
    
        <main>
            <div class="content">
                <a href="/" class="button-back"><i class="bi bi-arrow-left-short"></i></a>
                <h1><?= htmlspecialchars($individual_post->TITULO) ?></h1>
                <div class="post-info">
                    <div class="user">
                        <img src="/public/assets/Imagem-user.jpg" alt="icone do usuario">
                        <p>@<?= htmlspecialchars($individual_post->AUTOR_ID); ?></p>
                    </div>
                    <div class="date"><?= htmlspecialchars($individual_post->DATA_POSTAGEM); ?></div>
                </div>
                <img class="post-image" src="/public/<?= htmlspecialchars($individual_post->IMAGEM) ?>" alt="imagem do post">
                <div class="post-description">
                    <p><?= htmlspecialchars($individual_post->CONTEUDO); ?></p>
                </div>
                <div class="rating-content">
                    <div class="likes-content">
                        <div class="likes-icon">
                            <i class="bi bi-heart"></i>
                        </div>
                        <div class="likes-text">
                            <p>0</p><p>curtidas</p>
                        </div>
                    </div>
                    <div class="stars-content">
                        <div class="stars-text">
                            <p>Nota</p><p>do</p><p>autor:</p>
                        </div>
                        <div class="stars-icons">
                            <i class="bi bi-star-fill star1"></i>
                            <i class="bi bi-star-fill star2"></i>
                            <i class="bi bi-star-fill star3"></i>
                            <i class="bi bi-star-fill star4"></i>
                            <i class="bi bi-star-fill star5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>

    <?php require 'app/views/site/Footer.html'; ?>

    <script src="../../../public/js/individual_post.js"></script>
</html>