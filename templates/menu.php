<?php

$menus = $storefront->getMenus(); ?>

<?php foreach ($menus as $menu): ?>
    <?php if ($menu["menu_type"] === "link"): ?>
    <li>
        <a href="<?= $menu["page"]["slug"] ?>.php">
            <?= $menu["name"] ?>
        </a>
    </li>
    <?php elseif ($menu["menu_type"] === "dropdown"): ?>
    <li>
        <a href="<?= $menu["page"]["slug"] ?>.php">
            <?= $menu["name"] ?>
        </a>

        <ul class="hm-dropdown">
            <?php foreach ($menu["children"] as $child): ?>
            <?php if ($child["menu_type"] === "link"): ?>
                <li>
                    <a href="<?= $child["page"]["slug"] ?>.php">
                        <?= $child["name"] ?>
                    </a>
                </li>
            <?php elseif ($child["menu_type"] === "dropdown"): ?>
                <li>
                    <a href="<?= $child["page"]["slug"] ?>.php">
                        <?= $child["name"] ?>
                    </a>

                    <ul class="hm-dropdown">
                        <?php foreach ($child["children"] as $grandchild): ?>
                            <li>
                                <a href="<?= $grandchild["page"][
                                    "slug"
                                ] ?>.php">
                                    <?= $grandchild["name"] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </li>
    <?php endif; ?>
<?php endforeach; ?>
