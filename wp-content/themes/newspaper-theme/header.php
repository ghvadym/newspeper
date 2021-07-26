<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php the_title() ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="navigation">
    <div class="container">
        <div class="nav__body">
            <div class="nav__logo">
                <a class="logo" href="<?php echo home_url(); ?>">
                    Newspapers & <br>
                    Magazines
                </a>
            </div>
            <div class="nav__menu">
                <?php wp_nav_menu(['theme_location' => 'main_header']) ?>
            </div>
        </div>
    </div>
</div>
<main class="main">