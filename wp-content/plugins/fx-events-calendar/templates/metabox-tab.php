<div id="fx_events">
    <div class="fx_events__wrap">
        <div class="fx_event__row">
            <label for="fx_event__title">
                <?php _e('Title', 'fxevents') ?>
            </label>
            <input type="text"
                   name="fx_event__title"
                   id="fx_event__title"
                   value="<?php echo $data['fx_event__title'] ?>">
        </div>
        <div class="fx_event__row">
            <label for="fx_event__subtitle">
                <?php _e('Subtitle', 'fxevents') ?>
            </label>
            <input type="text"
                   name="fx_event__subtitle"
                   id="fx_event__subtitle"
                   value="<?php echo $data['fx_event__subtitle'] ?>">
        </div>
        <div class="fx_event__row">
            <label for="fx_event__date">
                <?php _e('Date event', 'fxevents') ?>
            </label>
            <input type="date"
                   name="fx_event__date"
                   id="fx_event__date"
                   value="<?php echo $data['fx_event__date'] ?>">
        </div>
    </div>
</div>