<?php
/**
 * Reusable star rating display component.
 *
 * @var int|float $starRating  Numeric rating (0–5); fractional values are rounded.
 */
$_stars = (int) round((float) ($starRating ?? 0));
?>
<div class="rating-box">
    <ul style="list-style:none;padding:0;margin:0;display:flex;gap:2px;">
        <?php foreach (range(1, 5) as $_s): ?>
        <li<?= $_s > $_stars ? ' class="silver-color"' : '' ?>>
            <i class="fa fa-star"></i>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
