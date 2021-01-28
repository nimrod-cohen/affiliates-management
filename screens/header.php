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
</head><?php
  $headerImage = get_header_image(); 
  $classes = $page."-page affiliates-page";
  $classes .= $headerImage && strlen($headerImage) > 0 ? "" : " no-logo";
  $classes .= is_user_logged_in() ? "" : " logged-out";
?><body class="<?php echo $classes;?>">
<header>
  <?php 
    global $wp;
    $logoutUrl = home_url(add_query_arg(["pg"=>"logout"],$wp->request));

    if(strlen($headerImage) > 0) { ?>
      <a class='logo' href="<?php echo get_home_url(); ?>">
        <img src="<?php echo esc_url(get_header_image()); ?>" alt="<?php echo esc_attr(get_bloginfo('title')); ?>" />
      </a>
  <?php } ?>
  <?php if( is_user_logged_in() ) { ?>
    <span class="login-link">Welcome <?php echo wp_get_current_user()->display_name;?>, <a class="link" href="<?php echo urldecode($logoutUrl); ?>">Log out</a></span>
  <?php } else { ?>
    <span class="login-link">Not logged in</span>
  <?php } ?>
  <div class='header-text'><?php echo get_bloginfo('name')?> - Affiliates system</div>
</header>