<?php
/**
 * Pagination widget.
 *
 * @var array<string, int> $pagination   current_page, last_page, total, per_page, from, to
 * @var array<string, mixed> $queryParams  base GET params to merge into page links
 */
if ($pagination['last_page'] <= 1) return;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="hiraola-paginatoin-area">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <ul class="hiraola-pagination-box">

                        <?php if ($pagination['current_page'] > 1): ?>
                        <li>
                            <a href="?<?= http_build_query(array_merge($queryParams, ['page' => 1])) ?>">
                                |<i class="ion-ios-arrow-left"></i>
                            </a>
                        </li>
                        <li>
                            <a class="Prev" href="?<?= http_build_query(array_merge($queryParams, ['page' => $pagination['current_page'] - 1])) ?>">
                                <i class="ion-ios-arrow-left"></i>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php
                        $window = 2;
                        $start  = max(1, $pagination['current_page'] - $window);
                        $end    = min($pagination['last_page'], $pagination['current_page'] + $window);
                        for ($p = $start; $p <= $end; $p++):
                        ?>
                        <li <?= $p === $pagination['current_page'] ? 'class="active"' : '' ?>>
                            <a href="?<?= http_build_query(array_merge($queryParams, ['page' => $p])) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                        <li>
                            <a class="Next" href="?<?= http_build_query(array_merge($queryParams, ['page' => $pagination['current_page'] + 1])) ?>">
                                <i class="ion-ios-arrow-right"></i>
                            </a>
                        </li>
                        <li>
                            <a class="Next" href="?<?= http_build_query(array_merge($queryParams, ['page' => $pagination['last_page']])) ?>">
                                >|
                            </a>
                        </li>
                        <?php endif; ?>

                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="product-select-box">
                        <div class="product-short">
                            <p>
                                Showing <?= $pagination['from'] ?> to <?= $pagination['to'] ?>
                                of <?= $pagination['total'] ?>
                                (<?= $pagination['last_page'] ?> Page<?= $pagination['last_page'] !== 1 ? 's' : '' ?>)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
