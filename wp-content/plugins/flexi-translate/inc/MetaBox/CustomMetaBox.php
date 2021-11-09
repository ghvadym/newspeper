<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 1:58 PM
 */

namespace MetaBox;


class CustomMetaBox
{
    public function __construct()
    {

        add_action('add_meta_boxes', [$this, 'wporg_add_custom_box']);
        add_action('admin_bar_menu', [$this, 'boxInTopPanel', 30] );
    }


    public function wporg_add_custom_box()
    {

        $args = array(
            'public'   => true,
            '_builtin' => false
        );

        $post_types = get_post_types( $args );

        $arr = [];
        foreach ($post_types as $key => $value ) {
          $arr[] = $value;
        }
        $screens = $arr;

        foreach ($screens as $screen) {

            add_meta_box(
                'wporg_box_id',
                'Перевод Поста',
                [$this, 'wporg_custom_box_html'],
                $screen
            );

        }
    }

    public function wporg_custom_box_html($post)
    {
        $langPosts = pll_get_post_translations($post->ID);
        $currentLang = pll_get_post_language($post->ID);
        $existsLangs = pll_languages_list();
            ?>
        <select name="wporg_field" id="select-lang" class="postbox">

            <?php foreach (pll_languages_list() as $item) :

                if ($currentLang === $item) {
                    continue;
                }

                ?>
                <option value="<?php echo $item ?>"><?php echo $item ?></option>
            <?php endforeach; ?>

        </select>
        <?php  if (count($langPosts) !== count($existsLangs)) : ?>
          <div class="button-click-me">
            <a href="#" data-domain="<?php echo get_home_url(); ?>" data-post-id="<?php echo $post->ID ?>" id="translate"><?php _e('Перевести', 'flexi'); ?></a>
          </div>
        <?php else :?>
            <p><?php _e('Уже существует перевод данного поста'); ?></p>
        <?php endif;
    }


}