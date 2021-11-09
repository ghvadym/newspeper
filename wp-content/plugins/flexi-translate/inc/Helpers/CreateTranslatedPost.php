<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 3:31 PM
 */

namespace Helpers;

class CreateTranslatedPost extends Functions
{
    public function __construct()
    {

        add_action('wp_ajax_createTranslatedPost', [$this,'createTranslatedPost']);
        add_action('wp_ajax_nopriv_createTranslatedPost',[$this, 'createTranslatedPost']);
    }

    function createTranslatedPost() {
        $lang = isset($_POST['lang']) ? $_POST['lang'] : false;
        $id = isset($_POST['id']) ? $_POST['id'] : false;
        $fields = isset($_POST['fieldArray']) ? $_POST['fieldArray'] : false;
        $translatedFields = json_decode(stripslashes($fields), true);
        $newTransaltedPost = $this->true_duplicate_post_as_draft( $translatedFields, $id ,$lang);
        $homeUrl = get_site_url();
        $homeUrl = str_replace('/\/([^\/]+)\/?$/', '', $homeUrl);
        wp_send_json(['res' => $newTransaltedPost, 'url' => $homeUrl]);
    }

}