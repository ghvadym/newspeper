<?php

namespace inc;

class Entries
{
    static function getEntryMessage()
    {
        $request = new Request($_POST);
        $entryId = $request->get('id');
        $message = DataBase::getEntryColumnById($entryId, 'message');
        $return = [
            'message' => $message[0]->message,
        ];

        wp_send_json_success($return);
    }

    static function loadMoreEntries()
    {
        $request = new Request($_POST);
        $entryPage = $request->get('page');

        $entries = DataBase::getAllEntries(10, $entryPage * 10);
        $totleEntries = DataBase::getMaxNumRows();

        $last = $totleEntries[0] <= ++$entryPage * 10;

        ob_start();
        foreach ($entries as $entry) {
            Entries::getSingleEntry($entry);
        }
        $html = ob_get_contents();
        ob_clean();

        wp_send_json_success(compact('html', 'last'));
    }

    static function getTemplate()
    {
        $template = is_user_logged_in() ? 'templates/form-entries-loggined.php'  :  'templates/form-entries-not-loggined.php' ;
        include PLUGIN_DIR . $template;
    }

    static function getSingleEntry($entry)
    {
        include PLUGIN_DIR . 'templates/form-single-entry.php';
    }
}
