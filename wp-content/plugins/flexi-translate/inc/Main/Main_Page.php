<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 11:52 AM
 */

namespace Main;


use Helpers\Functions;


class Main_Page extends Functions
{
    public function __construct()
    {

        add_action('flexi_content', [$this,'flexiMainPage']);

    }

    function flexiMainPage()
    {

        $postId = $_GET['id'] ?? '';
        $lang = $_GET['lang'] ?? '';


        ?>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Baloo+Tammudu+2:wght@500&display=swap" rel="stylesheet">
        <div class="main__wrapper-translate">
        <div class="button__container button-click-me">
            <a href="#" data-lang="<?php echo $lang ?>" data-id="<?php echo $postId; ?>" class="button__main-button"><?php _e
                ('Перевести', 'flexi') ?></a>
        </div>
        <div class="title__container shadow-container">
            <input type="text" value="<?php echo get_the_title($postId); ?>" class="title__goal">
            <input type="text" class="title__result">
        </div>
        <div class="editors__container shadow-container">

            <?php if ($postId):

                $content = get_post_field('post_content', $postId);
                $settings = [
                    'wpautop'          => 1,
                    'media_buttons'    => 0,
                    'textarea_name'    => 'input-goal',
                    'textarea_rows'    => 30,
                    'tabindex'         => null,
                    'editor_css'       => '',
                    'editor_class'     => '',
                    'teeny'            => 0,
                    'dfw'              => 0,
                    'tinymce'          => 1,
                    'quicktags'        => 1,
                    'drag_drop_upload' => false,

                ];

                $settings1 = [
                    'wpautop'          => 1,
                    'media_buttons'    => 0,
                    'textarea_name'    => 'input-result',
                    'textarea_rows'    => 30,
                    'tabindex'         => null,
                    'editor_css'       => '',
                    'editor_class'     => '',
                    'teeny'            => 0,
                    'dfw'              => 0,
                    'tinymce'          => 1,
                    'quicktags'        => 1,
                    'drag_drop_upload' => false,

                ];

                wp_editor($content, 'input-goal', $settings);
                wp_editor('', 'input-result', $settings1);

            endif; ?>
        </div>
        <?php

        $postFields = get_fields($postId);
        if ($postFields) {
            $i = 1;

            foreach ($postFields as $key => $value) {

                $i++;

                switch ($this->getTypeOfAcf($key)) {

                    case 'textarea' :
                        echo '<div class="textarea__wrapper shadow-container">
                        <textarea class="textarea__goal">' . $value . '</textarea>
                        <textarea data-totranslate="'. $key . '" class="textarea__result"></textarea>
                      </div>';
                        break;

                    case 'text' :
                        echo '<div class="text__wrapper shadow-container">
                        <input type="text" value="'. $value .'" class="text__goal">
                        <input data-totranslate="'. $key . '" type="text" class="text__result">
                      </div>';
                        break;

                    case 'wysiwyg' :
                        $settings = [
                            'wpautop'          => 1,
                            'media_buttons'    => 0,
                            'textarea_name'    => 'input-goal_' . $i,
                            'textarea_rows'    => 30,
                            'tabindex'         => null,
                            'editor_css'       => '',
                            'editor_class'     => '',
                            'teeny'            => 0,
                            'dfw'              => 0,
                            'tinymce'          => 1,
                            'quicktags'        => 1,
                            'drag_drop_upload' => false,

                        ];
                        $settings1 = [
                            'wpautop'          => 1,
                            'media_buttons'    => 0,
                            'textarea_name'    => 'input-result_' . $i,
                            'textarea_rows'    => 30,
                            'tabindex'         => null,
                            'editor_css'       => '',
                            'editor_class'     => '',
                            'teeny'            => 0,
                            'dfw'              => 0,
                            'tinymce'          => 1,
                            'quicktags'        => 1,
                            'drag_drop_upload' => false,

                        ];
                        echo '<div class="editor__wrapper shadow-container ">';
                        wp_editor($value, 'input-goal_' . $i , $settings);
                        wp_editor('', 'input-result_' . $i , $settings1);
                        echo '<span data-totranslate_editor="'. $key . '" class ="editor-id">input-result_' . $i . '</span>';
                        echo '</div>';
                        break;
                }
            }
        }
        $yostSocialTitle = get_post_meta($postId,'_yoast_wpseo_opengraph-title');
        $yostSeoTitle = YoastSEO()->meta->for_post($postId)->title;
        if ( $yostSeoTitle) {
            echo '<div class="text__wrapper shadow-container">
                        <input type="text" value="'. $yostSeoTitle .'" class="text__goal">
                        <input data-totranslate="_yoast_wpseo_title" type="text" class="text__result text__seoTitle">
                      </div>';
        }
        if ( $yostSocialTitle) {
            echo '<div class="text__wrapper shadow-container">
                        <input type="text" value="'. $yostSocialTitle[0] .'" class="text__goal">
                        <input data-totranslate="_yoast_wpseo_opengraph-title" type="text" class="text__result text__seoSocialTitle">
                      </div>';
        }
        ?>
          <div class="button-click-me result-btn">
            <a href="#" data-lang="<?php echo $lang ?>" data-id="<?php echo $postId; ?>" id="createPost"><?php _e('Создать Пост', 'flexi');?></a>
          </div>
            <div class="container-loader">
                <div class="loader">
                    <span></span>
                </div>
            </div>



        </div>
        <?php


    }

}
