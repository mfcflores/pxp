<?php
/**
 *	Installation related functions and actions.
 *
 *	@author 	Mark Francis C. Flores & Illumedia Outsourcing
 *	@category 	Admin
 *	@package 	PixelPartners/Classes
 *	@version    1.0.0
 */

if( !defined( 'ABSPATH' ) ) 
{
	exit; // Exit if accessed directly
}

if( !class_exists( 'PXP_Admin_Core' ) )
{

class PXP_Admin_Core
{
	public function __construct()
	{
	}
	
	/**
	 * Output Admin Clients Page.
	 */
	public static function pxp_admin_clients()
	{
		include_once( 'class-pxp-admin-clients.php' );
		
		PXP_Admin_Clients::pxp_admin_clients();
	}
	
	public static function adjust_credits()
	{
	
?>
				<div>
					<h1>Credit Adjustments</h1>
				</div>
					<div style=" width: 50%;">
						<h4>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent finibus orci non turpis suscipit ornare.
						</h4>
					</div>
					
		<table class="form-table " >
			<tbody>
				<tr valign="top">
					<th>Date</th>
					<th>Order Number</th>
					<th>Job Reference</th>
					<th>Contact Name</th>
					<th>Amount</th>
					<th>Description</th>
				</tr>
				<tr valign="top">
					<td>7/7/2007</td>
					<td>1234</td>
					<td>Sample</td>
					<td>John Doe</td>
					<td>$700</td>
					<td>Praesent finibus orci non turpis suscipit ornare.</td>
				</tr>
			</tbody>
		</table>
<?php	
	}
}

}

return new PXP_Admin_Core();

?>
