<div class="foot">
    <?php if ($total_pages > 1): ?>

        <div class="button-content">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>" class="botao_foot">
                    <i class="bi bi-caret-left-fill"></i>
                </a>
            <?php else: ?>
                <i class="bi bi-caret-left-fill" style="visibility: hidden;"></i>
            <?php endif; ?>
        </div>

        <div class="pagination-numbers">
            
            <?php if ($total_pages <= 7): ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <div class="page-slot center">
                        <a href="?page=<?= $i ?>" class="page-number <?= ($i == $current_page) ? 'current' : 'adjacent' ?>">
                            <?= $i ?>
                        </a>
                    </div>
                <?php endfor; ?>
            
            <?php else: ?>
                <?php
                // How many pages to show on each side of the current one
                $siblings = 1; 

                // --- Calculate window boundaries ---
                if (($current_page - $siblings) <= 1) { // Near the start
                    $window_start = 1;
                    $window_end = min($total_pages, 1 + ($siblings * 2));
                } elseif (($current_page + $siblings) >= $total_pages) { // Near the end
                    $window_end = $total_pages;
                    $window_start = max(1, $total_pages - ($siblings * 2));
                } else { // In the middle
                    $window_start = $current_page - $siblings;
                    $window_end = $current_page + $siblings;
                }
                ?>

                <?php if ($window_start > 1): ?>
                    <div class="page-slot center">
                        <a href="?page=1" class="page-number adjacent">1</a>
                    </div>
                <?php endif; ?>

                <?php if ($window_start > 2): ?>
                    <div class="page-slot center">
                        <span class="open-goto-modal page-number adjacent" style="cursor: pointer;" data-total-pages="<?= $total_pages ?>">...</span>
                    </div>
                <?php endif; ?>

                <?php for ($i = $window_start; $i <= $window_end; $i++): ?>
                    <div class="page-slot center">
                        <a href="?page=<?= $i ?>" class="page-number <?= ($i == $current_page) ? 'current' : 'adjacent' ?>">
                            <?= $i ?>
                        </a>
                    </div>
                <?php endfor; ?>

                <?php if ($window_end < $total_pages - 1): ?>
                    <div class="page-slot center">
                        <span class="open-goto-modal page-number adjacent" style="cursor: pointer;" data-total-pages="<?= $total_pages ?>">...</span>
                    </div>
                <?php endif; ?>

                <?php if ($window_end < $total_pages): ?>
                    <div class="page-slot center">
                        <a href="?page=<?= $total_pages ?>" class="page-number adjacent"><?= $total_pages ?></a>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>

        <div class="button-content">
            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>" class="botao_foot">
                    <i class="bi bi-caret-right-fill"></i>
                </a>
            <?php else: ?>
                <i class="bi bi-caret-right-fill" style="visibility: hidden;"></i>
            <?php endif; ?>
        </div>

    <?php elseif (!empty($posts)): ?>
        <div class="pagination-summary">
            <p>Exibindo todos os <?= count($posts) ?> posts.</p>
        </div>
    <?php else: ?>
        <div class="pagination-summary">
            <p>Nenhum post encontrado.</p>
        </div>
    <?php endif; ?>
</div>