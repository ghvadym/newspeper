<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 2:22 PM
 */

namespace Scripts;


class EnqueueScripts
{
    public $locate;
    public function __construct($locate)
    {
        $this->locate = $locate;
        add_action('admin_enqueue_scripts', [$this, 'flexi_enqueue_scripts']);
    }

    function flexi_enqueue_scripts()
    {

        wp_enqueue_style('flexi_style',   $this->locate . '/assets/css/main.css', array(), '1.0');
        wp_enqueue_script('jquery');
        wp_enqueue_script('main',   $this->locate . '/assets/js/mainPlugin.js', '', '', true);
        wp_localize_script('main', 'ajax_filter', array(
                'url' => admin_url('admin-ajax.php'),
            )
        );
    }

}