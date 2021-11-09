<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 11:45 AM
 */

namespace Helpers;


class Fetch_Response extends Functions
{

    public function __construct()
    {
        add_action('wp_ajax_postTranslate', [$this,'postTranslate']);
        add_action('wp_ajax_popUpCreate', [$this,'popUpCreate']);
        add_action('wp_ajax_popUpTranslate', [$this,'popUpTranslate']);
    }

    function postTranslate()
    {

        $lang = isset($_POST['lang']) ? $_POST['lang'] : false;
        $id = isset($_POST['id']) ? $_POST['id'] : false;
        $currentLang = pll_get_post_language($id);

        $content = get_post_field('post_content', $id);

        $title = $this->translateStr(get_the_title($id), $currentLang, $lang);
        $newStr = $this->flexiParser($content, $currentLang, $lang);

        $postFields = get_fields($id);
        $textAreas = [];
        foreach ($postFields as $key => $value) {
            switch ($this->getTypeOfAcf($key)) {
                case 'textarea' :
                    $translatedValue = $this->translateStr($value, $currentLang, $lang);
                    $textAreas['textarea'][] = $translatedValue;
                    break;
                case 'text' :
                    $translatedValue = $this->translateStr($value, $currentLang, $lang);
                    $textAreas['text'][] = $translatedValue;
                    break;
                case 'wysiwyg' :
                    $translatedValue = $this->flexiParser($value, $currentLang, $lang);
                    $textAreas['wysiwyg'][] = $translatedValue;
                    break;
            }
        }

        $yostSocialTitle ='';
        $yostSeoTitle = '';

        if (get_post_meta($id,'_yoast_wpseo_opengraph-title')) {
            $yostSocialTitle = $this->translateStr(get_post_meta($id,'_yoast_wpseo_opengraph-title')[0], $currentLang, $lang);
            $yostSocialTitle = str_replace('%% sep %%', '|', $yostSocialTitle);
        }
        if (YoastSEO()->meta->for_post($id)->title) {
            $yostSeoTitle = $this->translateStr(YoastSEO()->meta->for_post($id)->title, $currentLang, $lang);
            $yostSeoTitle = str_replace('%% sep %%', '|', $yostSeoTitle);
        }


        wp_send_json(['res' => $newStr, 'acf_blocks' => $textAreas, 'postTitle' => $title, 'yostSocialTitle' => $yostSocialTitle, 'yostSeoTitle' => $yostSeoTitle]);
    }

    function popUpCreate() {
        ob_start();
        ?>
        <div class="translateActive__popup">
            <div class="translateActive__wrapper">
                <div class="btn-close">
                    x
                </div>
                <div class="translateActive__textarea-wrapper">
                    <textarea id="textareaGoal"></textarea>
                    <textarea id="textareaResult"></textarea>
                </div>
                <div class="translateActive__inputs-wrapper">
                    <select id="selectGoal">
                        <option selected value="ru">Ру</option>
                        <option value="ua">Укр</option>
                    </select>
                    <select  id="selectResult">
                        <option value="ru">Ру</option>
                        <option selected value="ua">Укр</option>
                    </select>
                </div>
                <div class="button-click-me">
                    <a href="#"><?php _e('Перевести', 'flexi'); ?></a>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json(['res' => $html]);
    }

    function popUpTranslate() {
        $langCurrent = $_POST['langCurrent'] ?? '';
        $langResult = $_POST['langResult'] ?? '';
        $textToTranslate = $_POST['textToTranslate'] ?? '';
        $result =  $this->translateStr($textToTranslate, $langCurrent, $langResult);
        wp_send_json(['res' => $result]);
    }
}