<?php

namespace inc;

class Form
{
    static array $errors = [];

    static function formSubmit()
    {
        $request = new Request($_POST);
        $emptyKeys = Validator::getEmptyKeys($request->all());
        $email = $request->get('email');

        if (count($emptyKeys) > 0) {
            foreach ($emptyKeys as $keys) {
                self::$errors[$keys] = 'Field is empty!';
            }
        }

        if (self::$errors['email'] === NULL && !Validator::validateEmail($request->get('email'))) {
            self::$errors['email'] = 'Email is invalid!';
        }


        if (Validator::checkIfEmailIsUnique($email) && self::$errors['email'] === NULL) {
            self::$errors['email'] = 'Email is already in use!';
        }

        if (count(self::$errors) > 0) {
            wp_send_json_error(self::$errors);
        }

        DataBase::insertData($request->all());

        $return = [
            'message' => 'Thank you for sending us your feedback',
        ];
        wp_send_json_success($return);

    }

    static function getTemplate()
    {
        include PLUGIN_DIR . 'templates/form-template.php';
    }
}

