<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fcf0f0">
    <title>Bit's Toca</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/public/css/landing_page_styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    
    <?php require 'app/views/site/navbar.html'; ?>

    <main>
        <section class="hero-section">
            <div class="hero-with-image">
                <div class="hero-overlay">
                    <h1>SUA COMUNIDADE INDIE</h1>
                    <p>Descubra, analise e discuta os melhores jogos da cena independente</p>
                    <button class="cta-button">EXPLORAR O BLOG</button>
                </div>
            </div>
        </section>
        
        <section class="featured">
            <h2>PUBLICAÇÕES EM DESTAQUE</h2>
            <div class="featured-post">
                <div class="featured-container">
                    <?php if (!empty($featuredPosts)): ?>
                        <?php foreach ($featuredPosts as $post): ?>
                            <div class="featured-slide">
                                <img src="/public/<?= htmlspecialchars($post->IMAGEM) ?>" alt="<?= htmlspecialchars($post->TITULO) ?>" class="featured-image">
                                <div class="featured-text">
                                    <p class="featured-slide-title"><?= htmlspecialchars($post->TITULO) ?></p>
                                </div>
                                <div class="featured-description-overlay">
                                    <p class="featured-description">
                                        <?= htmlspecialchars(mb_strimwidth($post->CONTEUDO, 0, 200, "...")) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="featured-slide">
                            <img src="/public/assets/hero.png" alt="Sem posts" class="featured-image">
                            <div class="featured-text">
                                <p class="featured-slide-title">SEM DESTAQUES</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="featured-dots">
                    </div>
            </div>
        </section>

        <section class="carousel-section">
            <h2>PUBLICAÇÕES RECENTES</h2>
            <div class="carousel">
                <?php if (!empty($recentPosts)): ?>
                    <?php foreach ($recentPosts as $post): ?>
                        <div class="carousel-item">
                            <img src="/public/<?= htmlspecialchars($post->IMAGEM_RECENT) ?>" alt="<?= htmlspecialchars($post->TITULO) ?>">
                            <div class="carousel-item-glass">
                                <p class="carousel-item-title"><?= htmlspecialchars($post->TITULO) ?></p>
                            </div>
                            <div class="carousel-item-overlay">
                                <p class="carousel-item-description">
                                    <?= htmlspecialchars(mb_strimwidth($post->CONTEUDO, 0, 150, "...")) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                     <p style="text-align: center; color: white;">Nenhum post recente encontrado.</p>
                <?php endif; ?>
            </div>
            <div class="carousel-controls">
                <span class="left-arrow">&#9664;</span>
                <span class="right-arrow">&#9654;</span>
            </div>
        </section>

        <section class="about">
            <h2>SOBRE NÓS</h2>
            <div class="about-content">
                <p>
                    Bem-vindo à nossa casa, o ponto de encontro para todos os apaixonados pela cena independente de
                    jogos!
                    Nascemos de um desejo simples: criar um espaço dedicado a descobrir, analisar e celebrar as joias
                    escondidas e os grandes sucessos do universo indie. Mais do que um site, somos uma comunidade
                    vibrante
                    de jogadores, desenvolvedores e criadores de conteúdo unidos pela paixão por jogos autênticos e
                    inovadores.
                </p>
            </div>
        </section>
    </main>

    <?php require 'app/views/site/Footer.html'; ?>

    <script src="/public/js/landing_page.js"></script>
</body>

</html>