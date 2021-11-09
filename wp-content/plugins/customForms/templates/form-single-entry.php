<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<ul class="form_entries__item" data-id="<?php echo $entry->id ?>">
    <li class="form_entries__item__text"><p><?php echo $entry->first_name ?></p></li>
    <li class="form_entries__item__text"><p><?php echo $entry->last_name ?></p></li>
    <li class="form_entries__item__text"><p><?php echo $entry->email ?></p></li>
    <li class="form_entries__item__text"><p><?php echo $entry->subject ?></p></li>
</ul>
