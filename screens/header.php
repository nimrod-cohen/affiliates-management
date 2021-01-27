<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo get_bloginfo('name')?> - Affiliates system</title>
  <meta name="description" content="<?php echo get_bloginfo('name')?> - Affiliates system">
  <meta name="author" content="Affiliates Management">
  <?php 
    wp_print_styles();
    wp_print_scripts();
  ?>
</head>

<body class="<?php echo $page;?>-page affiliates-page">
<header>
  <?php 
    $headerImage = get_header_image();
    if(strlen($headerImage) > 0) { ?>
      <a class='logo' href="<?php echo get_home_url(); ?>">
        <img src="<?php echo esc_url(get_header_image()); ?>" alt="<?php echo esc_attr(get_bloginfo('title')); ?>" />
      </a>
  <?php } ?>
  <?php if( is_user_logged_in() ) { ?>
    <a class="link login-link" href="<?php echo wp_logout_url(); ?>">Log out</a>
  <?php } ?>
  <div class='header-text'><?php echo get_bloginfo('name')?> - Affiliates system</div>
</header>