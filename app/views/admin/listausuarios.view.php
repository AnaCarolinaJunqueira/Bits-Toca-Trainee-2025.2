<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bits toca - Admin Usuários</title>

    <link rel="stylesheet" href="/public/css/listausuarios.css">
    <link rel="stylesheet" href="/public/css/modals.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=VT323:wght@400&family=Roboto:wght@300;400;500;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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
                    <h1>Tabela de Usuários</h1>
                </div>
                <div class="espaço"> </div>
            </div>
            <div class="botoes">
                <form method="GET" action="/admin/listausuarios" class="barra-pesquisa">
                    <img src="/public/assets/icon_pesquisa.png" alt="icone de pesquisa">
                    <input type="text" id="pesquisa" name="search" placeholder="Pesquisar..." value="<?= htmlspecialchars($search_term ?? '') ?>">
                    <button type="submit" style="display: none;"></button>
                </form>
                <div class="botao-post" onclick="abrirModal('modal-novo-usuario')">
                    <p> NOVO USUÁRIO</p>
                    <i class="bi bi-plus-circle"></i>
                </div>
            </div>
            <div class="container-tabela">
                <div class="tabela">
                    <table>
                        <thead>
                            <tr class="header">
                                <th class="user-col-id">
                                    <p>ID</p>
                                </th>
                                <th class="user-col-name">
                                    <p>NOME</p>
                                </th>
                                <th class="user-col-email">
                                    <p>E-MAIL</p>
                                </th>
                                <th class="user-col-action">
                                    <p>AÇÕES</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td class="user-col-id">
                                        <p><?= $user->ID; ?></p>
                                    </td>
                                    <td class="user-col-name">
                                        <p><?= htmlspecialchars($user->NOME); ?></p>
                                    </td>
                                    <td class="user-col-email">
                                        <p><?= htmlspecialchars($user->EMAIL); ?></p>
                                    </td>
                                    <td class="user-col-action">
                                        <div>
                                            <div class="button-content">
                                                <button class="btn-modal btn-view-user"
                                                    data-id="<?= $user->ID; ?>"
                                                    data-nome="<?= htmlspecialchars($user->NOME); ?>"
                                                    data-email="<?= htmlspecialchars($user->EMAIL); ?>"
                                                    data-avatar="<?= htmlspecialchars($user->AVATAR); ?>"
                                                    data-is_admin="<?= $user->IS_ADMIN; ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="button-content">
                                                <button class="btn-modal btn-edit-user"
                                                    data-id="<?= $user->ID; ?>"
                                                    data-nome="<?= htmlspecialchars($user->NOME); ?>"
                                                    data-email="<?= htmlspecialchars($user->EMAIL); ?>"
                                                    data-avatar="<?= htmlspecialchars($user->AVATAR); ?>"
                                                    data-is_admin="<?= $user->IS_ADMIN; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                            <div class="button-content">
                                                <button class="btn-modal btn-delete-user"
                                                    data-id="<?= $user->ID; ?>">
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
                <?php
                $figs = range(1, 50);
                $random_keys = array_rand($figs, 3);
                ?>
                <img src="/public/assets/figurinhas/<?= $figs[$random_keys[0]] ?>.png" class="figurinha" id="fig1" alt="figurinha 1">
                <img src="/public/assets/figurinhas/<?= $figs[$random_keys[1]] ?>.png" class="figurinha" id="fig2" alt="figurinha 2">
                <img src="/public/assets/figurinhas/<?= $figs[$random_keys[2]] ?>.png" class="figurinha" id="fig3" alt="figurinha 3">

                <?php require 'app/views/admin/components/paginacao.php'; ?>
            </div>
        </div>
    </main>

    <?php require 'app/views/admin/modals/modal-novo-usuario.html'; ?>
    <?php require 'app/views/admin/modals/modal-editar-usuario.html'; ?>
    <?php require 'app/views/admin/modals/modal-deletar-usuario.html'; ?>
    <?php require 'app/views/admin/modals/modal-visualizar-usuario.html'; ?>
    <?php require 'app/views/admin/modals/modal-goto-page.html'; ?>
    
    <script src="/public/js/listausuarios.js"></script>
    <script src="/public/js/modals-usuarios.js"></script>
</body>

</html>