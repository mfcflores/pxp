<?php
require __DIR__ . '/vendor/autoload.php';
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

Class PXP_PayPal
{
	public $paypalMode; 	// Mode: Live or Sandbox
	public $clientID; 		// Client ID
	public $clientSecret; 	// Client Secret
	public $returnUrl;		// Set Return URL Link upon successful transaction.
	public $cancelUrl;	// Set URL Link to return when transaction is cancelled.
	public $debugMode;		// Set enable or disable debugging mode.
	
	public $payer;
	public $item;
	public $item2;
	public $itemList;
	public $details;
	public $amount;
	public $transaction;
	public $redirectUrls;
	public $payment;
	public $execution;
	
	public $item_name;
	public $price;
	public $currency;
	
	public $default_page;
	public $post_id;
	public $renewal_page;
	public $payment_method;
	
	public function __construct()
	{
		$this->payer 		= new Payer();
		$this->item 		= new Item();
		$this->item2 		= new Item();
		$this->itemList		= new ItemList();
		$this->details 		= new Details();
		$this->amount		= new Amount();
		$this->transaction	= new Transaction();
		$this->redirectUrls	= new RedirectUrls();
		$this->payment		= new Payment();
		$this->execution 	= new PaymentExecution();
		
		$pxp_paypal = get_option( 'pxp_paypal_settings' );
		
		$settings = array (
			'currency'		=> 'USD',
			'mode'			=> ( $pxp_paypal['paypal_mode'] == 1 ) ? "live" : "sandbox",
			'client_id'		=> $pxp_paypal['client_id'],
			'client_secret'	=> $pxp_paypal['client_secret'],
			'debug'			=> ( $pxp_paypal['debug_mode'] == 1 ) ? true : false
			//'return'		=> $pxp_paypal[4],
			//'cancel_return'	=> $pxp_paypal[5],
		);
		
		$this->item_name 	= 'None';
		$this->price		= 0.00;
		$this->currency		= $settings['currency'];
		
		$this->paypalMode	= $settings['mode'];
		$this->clientID		= $settings['client_id'];
		$this->clientSecret	= $settings['client_secret'];
		//$this->returnUrl	= $settings['return'];
		//$this->cancelUrl	= $settings['cancel_return'];
		$this->debugMode	= $settings['debug'];
	}
	
	public function getApiContext()
	{
		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$this->clientID, //Client ID
				$this->clientSecret //Client Secret
			)
		);

		$apiContext->setConfig(
			array(
				'mode' => $this->paypalMode, // Mode
				'http.ConnectionTimeOut' => 30,
				'log.LogEnabled' => $this->debugMode,  
				'log.FileName' => '../PayPal.log',
				'log.LogLevel' => 'FINE'
			)
		);
		
		return $apiContext;
	}
	
	public function getBaseUrl() 
	{
		$protocol = 'http';
		if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) 
		{
			$protocol .= 's';
			$protocol_port = $_SERVER['SERVER_PORT'];
		} 
		else 
		{
			$protocol_port = 80;
		}

		$host = $_SERVER['HTTP_HOST'];
		$port = $_SERVER['SERVER_PORT'];
		$request = $_SERVER['PHP_SELF'];
		return dirname($protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request);
	}
	
	public function setPaymentMethod($method)
	{	
		$this->payment_method = $method;
		
		$method = 'paypal';
		
		$this->payer->setPaymentMethod($method); // Set Payment Method
	}
	
	public function setCreditBlock( $post_id )
	{
		$credit_price 	= get_post_meta( $post_id, '_credit_price', true);
		$credit_amount 	= get_post_meta( $post_id, '_credit_amount', true);
		$credit_bonus	= get_post_meta( $post_id, '_credit_bonus', true);
		
		$total_credit	= $credit_amount + ( $credit_amount * ( $credit_bonus / 100 ) );
		
		$this->item_name 	= 'Pixel Partners [' . $total_credit . ' Credits]';
		$this->price		= $credit_price;
	}
	
	public function setItems()
	{
		$this->item->setName($this->item_name) // Item Name
			->setCurrency($this->currency) // Default Currency
			->setQuantity(1) // Quantity of Item
			->setPrice($this->price); // Item Price

		$this->itemList->setItems(array($this->item));
	}
	
	public function setAmount()
	{	
		$this->amount->setCurrency($this->currency)
			->setTotal($this->price); // Total Amount of Payment
	}
	
	public function setTransaction()
	{
		$this->transaction->setAmount($this->amount)
			->setItemList($this->itemList)
			->setDescription( 'PixelPartners credit block.' );
	}
	
	public function setDefaultPage( $page )
	{
		$this->default_page = $page;
	}
	
	public function setRedirectUrls()
	{
		$baseUrl = $this->getBaseUrl();
		
		$defaultUrl = $this->default_page;
		
		$returnUrl 	= $defaultUrl . '&success=true&checkout=creditblocks';
		$cancelUrl 	= $defaultUrl . '&success=false&checkout=creditblocks';
		
		$this->redirectUrls->setReturnUrl("$returnUrl")
			->setCancelUrl("$cancelUrl");
	}
	
	public function setPayment()
	{
		$this->payment->setIntent("sale")
			->setPayer($this->payer)
			->setRedirectUrls($this->redirectUrls)
			->setTransactions(array($this->transaction));
	}
	
	public function createPayment()
	{
		try 
		{
			$this->payment->create($this->getApiContext());
		} 
		catch (PayPal\Exception\PPConnectionException $ex) 
		{
			echo "Exception: " . $ex->getMessage() . PHP_EOL;
			var_dump($ex->getData());	
			exit(1);
		}
	}
	
	public function getPaymentId()
	{
		return $this->payment->getId();
	}
	
	public function getRedirectLink()
	{
		foreach($this->payment->getLinks() as $link) 
		{
			if($link->getRel() == 'approval_url') 
			{
				$redirectUrl = $link->getHref();
				break;
			}
		}
		
		return $redirectUrl;
	}
	
	public function executePayment($paymentId, $payerID)
	{
		try
		{
			$this->payment = Payment::get($paymentId, $this->getApiContext());
			$this->execution->setPayerId($payerID);
			
			$result = $this->payment->execute($this->execution, $this->getApiContext());
		}
		catch (PayPal\Exception\PPConnectionException $ex) 
		{
			if($this->debugMode)
			{
				echo "Exception:" . $ex->getMessage() . PHP_EOL;
				var_dump($ex->getData());
			}
			return false;
		}
		
		return true;
	}
}
?>