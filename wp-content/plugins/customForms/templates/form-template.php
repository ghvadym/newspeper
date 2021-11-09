<?php
if (!defined('ABSPATH')) {
    exit;
}
$userData = wp_get_current_user();
?>

<form id="flexi_form" class="flexi_form">
    <label for="first_name" class="flexi_form__input">First Name
        <input name="first_name" type="text" value="<?php echo $userData ? $userData->first_name : '' ?>">
    </label>
    <label for="last_name" class="flexi_form__input">Last Name
        <input name="last_name" type="text" value="<?php echo $userData ? $userData->last_name : '' ?>">
    </label>
    <label for="email" class="flexi_form__input">Email
        <input name="email" type="email" value="<?php echo $userData ? $userData->user_email : '' ?>">
    </label>
    <label for="subject" class="flexi_form__input">Subject
        <input name="subject" type="text">
    </label>
    <label for="message" class="flexi_form__textarea">Message
        <textarea name="message"></textarea>
    </label>
    <button class="flexi_form__submit" type="submit">Submit</button>
</form>
