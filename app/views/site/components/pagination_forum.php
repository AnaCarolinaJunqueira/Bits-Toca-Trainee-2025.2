<div class="foot">
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <?php
        $queryParts = [];

        $term = $searchTerm ?? '';
        $cat = $currentCategory ?? '';

        if (!empty($term)) $queryParts[] = 'search=' . urlencode($term);
        if (!empty($cat)) $queryParts[] = 'category=' . urlencode($cat);

        $queryString = !empty($queryParts) ? '&' . implode('&', $queryParts) : '';

        $curr = $currentPage ?? 1;
        $total = $totalPages;
        ?>

        <div class="button-content">
            <?php if ($curr > 1): ?>
                <a href="?page=<?= $curr - 1 ?><?= $queryString ?>" class="botao_foot">
                    <i class="bi bi-caret-left-fill"></i>
                </a>
            <?php else: ?>
                <i class="bi bi-caret-left-fill" style="visibility: hidden;"></i>
            <?php endif; ?>
        </div>

        <div class="pagination-numbers">
            <div class="page-slot center">
                <a href="?page=1<?= $queryString ?>" class="page-number <?= ($curr == 1 ? 'current' : 'adjacent') ?>">1</a>
            </div>

            <?php
            // Calculate window around current page
            $window = 1;
            $start = max(2, $curr - $window);
            $end = min($total - 1, $curr + $window);
            ?>

            <?php if ($start > 2): ?>
                <div class="page-slot center">
                    <span class="open-goto-modal page-number adjacent" style="cursor: pointer;" data-total-pages="<?= $total ?>">...</span>
                </div>
            <?php endif; ?>

            <?php for ($i = $start; $i <= $end; $i++): ?>
                <div class="page-slot center">
                    <a href="?page=<?= $i ?><?= $queryString ?>" class="page-number <?= ($i == $curr) ? 'current' : 'adjacent' ?>">
                        <?= $i ?>
                    </a>
                </div>
            <?php endfor; ?>

            <?php if ($end < $total - 1): ?>
                <div class="page-slot center">
                    <span class="open-goto-modal page-number adjacent" style="cursor: pointer;" data-total-pages="<?= $total ?>">...</span>
                </div>
            <?php endif; ?>

            <?php if ($total > 1): ?>
                <div class="page-slot center">
                    <a href="?page=<?= $total ?><?= $queryString ?>" class="page-number <?= ($curr == $total ? 'current' : 'adjacent') ?>"><?= $total ?></a>
                </div>
            <?php endif; ?>
        </div>

        <div class="button-content">
            <?php if ($curr < $totalPages): ?>
                <a href="?page=<?= $curr + 1 ?><?= $queryString ?>" class="botao_foot">
                    <i class="bi bi-caret-right-fill"></i>
                </a>
            <?php else: ?>
                <i class="bi bi-caret-right-fill" style="visibility: hidden;"></i>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</div>