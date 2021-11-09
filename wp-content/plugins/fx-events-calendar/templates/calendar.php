<?php
use Includes\Functions;
$days = Functions::daysOfMonth();
$months = Functions::getMonths();
?>

<div class="calendar" id="calendar">
    <div class="calendar__body">
        <div class="calendar__head">
            <div class="calendar__months">
                <div class="months__selected" data-month="<?php echo date('n') ?>">
                    <?php echo date('F') ?>
                </div>
                <div class="months__list">
                    <?php foreach ($months as $name => $number) : ?>
                        <div class="month__item" data-month="<?php echo $number ?>">
                            <?php echo $name ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        <div class="calendar__loop">
            <?php require Functions::getPath('days-of-month') ?>
        </div>
    </div>
</div>