<?php
/**
 *	Client Transaction page.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Client
 *	@package 	PixelPartners/Classes/Client
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Client_Credits' ) )
{

class PXP_Client_Credits
{
	public function __construct()
	{
		add_action( 'admin_init', array( $this, 'pxp_checkout_credit' ) );
		add_action( 'admin_init', array( $this, 'pxp_execute_checkout' ) );
	}
	
	/**
	 * Display Client Credits Page.
	 */
	public static function output()
	{
		global $user_ID;
		
		$current_credits = get_user_meta( $user_ID, 'pxp_user_credits', true );
		$current_credits = ( $current_credits != NULL ) ? $current_credits : 0;
		
		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'pxp_credit_blocks',
			'order'				=> 'ASC',
			'orderby'			=> 'meta_value_num',
			'meta_key'			=> '_credit_amount'
		);
		
		$query = new WP_Query( $args );
?>


		<div id="pxp-credits-page">
			<div class="pxp-credit-box">
				<div class="pxp-credit-column50">
					<h1>Purchase Credits</h1>
				</div>
				<div class="pxp-credit-column50 text-right">
					<h3 style="margin-right:15px;">Remaining Balance:&nbsp;<span class="text-red"><?php echo $current_credits; ?></span> Credits</h3>
				</div>
			</div>
			
			<div class="clear"></div>
<?php 	
			if( isset( $_REQUEST['checkout'] ) ):
				$update = 'credit_block_checkout_' . $_REQUEST['checkout'];
				
				pxp_admin_notification_message( $update );
			endif;
?>
			
			<div class="pxp-credit-box">
				<h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare. Quisque odio enim, vulputate in felis vel, tincidunt rutrum ex.  Praesent finibus orci non turpis suscipit ornare.
				</h4>
			</div>
			
			<div class="pxp-credit-box text-center">
			<?php
				global $post;
				
				if( $query->have_posts() ) :
					while( $query->have_posts() ) :
						$query->the_post();
						
						$post_id		= $post->ID;
						$credit_price 	= get_post_meta( $post_id, '_credit_price', true);
						$credit_amount 	= get_post_meta( $post_id, '_credit_amount', true);
						$credit_bonus	= get_post_meta( $post_id, '_credit_bonus', true);
						$credit_icon	= get_the_post_thumbnail( $post_id, 'post-thumbnail' );						
						
						$total_credit	= $credit_amount + ( $credit_amount * ( $credit_bonus / 100 ) );
			?>
						<div class="pxp-credit">
							<div class="row">
								<div class="column-left">
									<div class="credit-icon"><?php echo $credit_icon; ?></div>
								</div>
								<div class="column-right">	
									<h3>[<?php _e( $total_credit . ' Credits' ); ?>]</h3>
								</div>
							</div>
							<div id="content" class="row">
								<p><?php _e( '$' . $credit_price ); ?></p>
								<p><?php _e( $credit_amount . ' Credits' ); ?></p>
								<p><?php _e( '+' . $credit_bonus . '% Credits' ); ?></p>
							</div>
							<div id="btn" class="row">
								<form action="" method="POST">
									<?php wp_nonce_field( 'pxp_checkout_credit', 'pxp_checkout_credit_nonce' ); ?>
									<input type="hidden" name="pxp_admin_a" value="checkout_credit" /> 
									<button type="submit" name="credit_block" id="credit-block-<?php echo $post_id; ?>" value="<?php echo $post_id; ?>" class="btn">Buy</button>
								</form>
							</div>
						</div>
			<?php
					endwhile;
				endif;
			?>
			</div>
			
			<div class="pxp-credit-box">
				<h4>For any concerns regarding PxP Credits, please email: <a href="mailto:alan@pixelpartnershq.com">alan@pixelpartnershq.com</a></h4>
			</div>

			<div class="clearfix"></div>
		</div>
<?php
	}
	
	public function pxp_checkout_credit()
	{
		global $pxp_session, $user_ID;
		
		if( !isset( $_POST['pxp_checkout_credit_nonce'] ) ) { 
			return false;
		}

		$nonce = $_POST['pxp_checkout_credit_nonce'];

		if( !wp_verify_nonce( $nonce, 'pxp_checkout_credit' ) ) { 
			return false;
		}
		
		$post_id = $_POST['credit_block'];
		
		$credit_price 	= get_post_meta( $post_id, '_credit_price', true);
		$credit_amount 	= get_post_meta( $post_id, '_credit_amount', true);
		$credit_bonus	= get_post_meta( $post_id, '_credit_bonus', true);
		$total_credit	= $credit_amount + ( $credit_amount * ( $credit_bonus / 100 ) );
		
		$pxp_session['credit_blocks_checkout'] = array(
			'post_id'		=> $post_id,
			'price'			=> $credit_price,
			'amount'		=> $credit_amount,
			'credit_bonus'	=> $credit_bonus,
			'total_credit'	=> $total_credit,
			'user_id'		=> $user_ID
		);
		
		// Check if current user is admin.
		if( current_user_can( 'manage_options' ) )
			$defaultPage = admin_url( 'users.php?page=pxp-client-credits' );
		else
			$defaultPage = admin_url( 'profile.php?page=pxp-client-credits' );

		include_once( PXP_FILE_PATH . '/includes/paypal/class.paypal.php' );
		$paypal = new PXP_PayPal();
		
		$paypal->setCreditBlock( $post_id );
		$paypal->setDefaultPage( $defaultPage );
		$paypal->setPaymentMethod( "paypal" );
		$paypal->setItems();
		$paypal->setAmount();
		$paypal->setTransaction();
		$paypal->setRedirectUrls();
		$paypal->setPayment();
		$paypal->createPayment();
		
		$payment_id = $paypal->getPaymentId();
		$redirect_url = $paypal->getRedirectLink();

		$pxp_session['credit_blocks_checkout']['payment_id'] = $payment_id;
		
		if( isset( $redirect_url ) )
		{
			header("Location: $redirect_url");
			exit;
		}
	}
	
	/**
	 * Process and execute transaction paypal payment.
	 */
	public function pxp_execute_checkout()
	{
		global $pxp_session;
		
		// Check current page.
		if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'pxp-client-credits' ):
			// Check checkout.
			if( isset( $_REQUEST['checkout'] ) && $_REQUEST['checkout'] == "creditblocks" ) :
				// Check if success.
				if( isset( $_REQUEST['success'] ) && $_REQUEST['success'] == "true" ) :
					$transaction = pxp_get_session( 'credit_blocks_checkout' );
					
					$paymentId 	= $_REQUEST['paymentId'];
					$PayerID	= $_REQUEST['PayerID'];
					
					include_once( PXP_FILE_PATH . '/includes/paypal/class.paypal.php' );
					$paypal = new PXP_PayPal();
					
					$verify = $paypal->executePayment( $paymentId, $PayerID );
					
					if( $verify )
					{
						$current_credits = get_user_meta( $transaction['user_id'], 'pxp_user_credits', true );
						$update_credits = $current_credits + $transaction['total_credit'];
						
						update_user_meta( $transaction['user_id'], 'pxp_user_credits', $update_credits );
						
						$post = array(
							'post_type'		=> 'pxp_transactions',
							'post_author'	=> $transaction['user_id'],
							'post_title'	=> 'Transaction [' . $transaction['total_credit'] . ' Credits]',
							'post_status'	=> 'private',
							'post_content'	=> 'Bought <strong>[' . $transaction['total_credit'] . ' Credits]</strong> for <strong>$' . number_format( $transaction['price'], 2) . '</strong>'
						);
						
						// Record transaction.
						$post_id = wp_insert_post( $post );
						
						wp_redirect( admin_url( 'profile.php?page=pxp-client-credits&checkout=success' ) );
						exit();
					}
					else
					{
						wp_redirect( admin_url( 'profile.php?page=pxp-client-credits&checkout=error' ) );
						exit();
					}
				else :
					wp_redirect( admin_url( 'profile.php?page=pxp-client-credits&checkout=cancelled' ) );
					exit();
				endif;
			endif;
		endif;
	}
}

}

return new PXP_Client_Credits();

?>