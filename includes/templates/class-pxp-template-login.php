<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Template Name: Login Page
 */
?>

<?php
	$post_id = get_option( 'pxp_registration_page' );
?>

<h1 class="page-title">Login</h1>

<div id="login-area">

	<form class="form" method="post" action="<?php echo site_url( 'wp-login.php' ); ?>">
		<input name="log" type="text" placeholder="User Login" autofocus />
		<br>
		<input name="pwd" type="password" class="input-text" placeholder="Password" />
		<br>
		<span class="remember"><input type="checkbox" value="forever" name="rememberme"> Remember Me</span>
		<br>
		<input id="wp-submit" type="submit" value="LOGIN" class="button" name="wp-submit">
		<br>
		<a href="#">Forgot Password</a> | <a href="<?php echo get_permalink( $post_id ); ?>">Register</a>
	
		<input type="hidden" value="<?php echo site_url(); ?>" name="redirect_to"></input>
		<input type="hidden" value="1" name="testcookie"></input>
	</form>

</div> <!-- end of login area -->