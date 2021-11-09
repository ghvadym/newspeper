<?php

foreach ($days as $day): ?>
    <div class="calendar__day">
        <div class="calendar__numb">
            <?php echo $day ?>
            <?php

            ?>
            <div class="calendar__posts"></div>
        </div>
    </div>
<?php
endforeach;