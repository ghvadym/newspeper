<?php

if (!defined('ABSPATH')) {
    exit;
}

use  inc\DataBase;
use inc\Entries;

$entries = DataBase::getAllEntries();
$totleEntries = DataBase::getMaxNumRows();

?>

<div class="form_entries">
    <div class="items_wrapper">
        <?php foreach ($entries as $entry) {
            Entries::getSingleEntry($entry);
        }
        ?>
    </div>
    <?php if ((int)$totleEntries[0] > 10) : ?>
        <button type="button" data-page="1">Load More</button>
    <?php endif; ?>

</div>
