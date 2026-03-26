<?php
/** @var array<int, array<string, mixed>> $categories */
/** @var int|null $selectedCategoryId */
/** @var string $sectionId */

foreach ($categories as $category):
    $categoryId = (int) ($category["id"] ?? 0);
    $categoryName = trim((string) ($category["name"] ?? "Category"));
    $isActiveCategory = $selectedCategoryId === $categoryId;
    $categoryAnchor = "#" . $sectionId;
    ?>
    <li>
        <a
            data-tagged-products-tab
            data-category-id="<?= htmlspecialchars((string) $categoryId) ?>"
            class="<?= $isActiveCategory ? "active" : "" ?>"
            href="<?= htmlspecialchars($categoryAnchor) ?>"
        ><span><?= htmlspecialchars($categoryName) ?></span></a>
    </li>
<?php endforeach; ?>
