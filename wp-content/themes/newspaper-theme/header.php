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
<header id="navigation">
    <div class="nav">
        <div class="container">
            <div class="nav__body">
                <div class="nav__logo">
                    <a class="logo" href="<?php echo home_url(); ?>"><?php bloginfo('title') ?></a>
                </div>
                <div class="nav__menu">
                    <?php wp_nav_menu(['theme_location' => 'main_header']) ?>
                </div>
                <div class="nav__burger"></div>
            </div>
        </div>
    </div>
</header>
<main class="main">