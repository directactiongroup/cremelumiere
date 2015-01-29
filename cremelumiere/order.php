<?
// Site-specific config file
$siteConfig = 'cremelumiere';

// Bootstrap
$scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/cremelumiere'));
require_once($scriptPath.'/app/bootstrap.php');

// Turn error reporting on by removing the slashes for the two lines below
//error_reporting(-1);
//ini_set('display_errors',1);

// Create objects
$customerObj = new customers;
$triangleObj = new triangleMedia;

// Define variables
$page = (isset($_REQUEST['page'])) ? $cleanObj->cleanAlphaLower($_REQUEST['page']) : false;
$order = (isset($_REQUEST['order'])) ? $cleanObj->cleanAlphaLower($_REQUEST['order']) : false;
$order_id = (isset($_REQUEST['order_id'])) ? $cleanObj->cleanAlphaNum($_REQUEST['order_id']) : false;
$shipping_id = (isset($_REQUEST['shipping_id'])) ? $cleanObj->cleanAlphaNum($_REQUEST['shipping_id']) : false;
$name_first = (isset($_REQUEST['name_first'])) ? $cleanObj->cleanName($_REQUEST['name_first']) : false;
$name_last = (isset($_REQUEST['name_last'])) ? $cleanObj->cleanName($_REQUEST['name_last']) : false;
$address = (isset($_REQUEST['address'])) ? $cleanObj->cleanName($_REQUEST['address']) : false;
$city = (isset($_REQUEST['city'])) ? $cleanObj->cleanName($_REQUEST['city']) : false;
$state = (isset($_REQUEST['state'])) ? $cleanObj->cleanAlphaUpper($_REQUEST['state']) : false;
$zip = (isset($_REQUEST['zip'])) ? $cleanObj->cleanZip($_REQUEST['zip']) : false;
$phone = (isset($_REQUEST['phone'])) ? $cleanObj->cleanInt($_REQUEST['phone']) : false;
$country = 'US';
$cc_type = (isset($_REQUEST['cc_type'])) ? $cleanObj->cleanAlphaUpper($_REQUEST['cc_type']) : false;
$cc_num = (isset($_REQUEST['cc_num'])) ? $cleanObj->cleanInt($_REQUEST['cc_num']) : false;
$exp_month = (isset($_REQUEST['exp_month'])) ? $cleanObj->cleanInt($_REQUEST['exp_month']) : false;
$exp_year = (isset($_REQUEST['exp_year'])) ? $cleanObj->cleanInt($_REQUEST['exp_year']) : false;
$cc_code = (isset($_REQUEST['cc_code'])) ? $cleanObj->cleanInt($_REQUEST['cc_code']) : false;
$product_id = (isset($_REQUEST['product_id'])) ? $cleanObj->cleanAlphaNum($_REQUEST['product_id']) : false;
$agree_terms = (isset($_REQUEST['agree_terms'])) ? $cleanObj->cleanAlphaLower($_REQUEST['agree_terms']) : false;
$signup_pass = true;
$frmMsg = '';
$arrProducts = array('D6VWR72'=>array('14 Day Trial of Ultra Garcinia Slim','9--23','30','47','4.95','Free Shipping','0.00'),'C5PW5RFX'=>array('1 Bottle of Ultra Garcinia Slim','10--23','','','79.95','Standard Shipping','9.95'),'JIVTIXSS'=>array('Buy 2 Bottles, Get 1 Free','11--23','','','98.00','Free Shipping','0.00'),'SRTCV41'=>array('Buy 3 Bottles, Get 2 Free','12--23','','','148.00','Free Shipping','0.00'));
$arrProductsKeys = array_keys($arrProducts);
$customFields = array();

// Write no-cache headers
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Check order session
if (!$_SESSION['order_sess']) {
	$_SESSION['order_sess'] = md5(date('Y-m-d H:i:s'));
}

// Check cid session
if (isset($_SESSION['CID'])) {
	$customerObj->getCustomer($_SESSION['CID']);
	if ($getSuccess == true) {
		$customer_id = $getCustomerID;
		// Billing Info
		$billing_name_first = $getCustomerBillingNameFirst;
		$billing_name_last = $getCustomerBillingNameLast;
		$billing_address = $getCustomerBillingAddress;
		$billing_city = $getCustomerBillingCity;
		$billing_state = $getCustomerBillingState;
		$billing_zip = $getCustomerBillingZip;
		$billing_country = $getCustomerBillingCountry;
		$billing_phone = $getCustomerBillingPhone;
		$email = $getCustomerEmail;
		// Pre-populate empty address fields
		if ($order == false) {
			// Shipping Info
			$shipping_id = $getCustomerShippingArray[0][0];
			$name_first = $getCustomerShippingArray[0][1];
			$name_last = $getCustomerShippingArray[0][2];
			$address = $getCustomerShippingArray[0][3];
			$city = $getCustomerShippingArray[0][5];
			$state = $getCustomerShippingArray[0][6];
			$zip = $getCustomerShippingArray[0][7];
			$phone = $getCustomerShippingArray[0][9];
		}
	}
	else {
		unset($_SESSION['CID']);
		header('location: index.php');
	}
}
else {
	header('location: index.php');
}

// Check signup
if ($order == 'y'  && $order_id == $_SESSION['order_sess']) {
	// Check for required shipping/personal fields
	if ($name_first && $name_last && $address && $city && $state && $zip && $phone && $product_id && $cc_type && $cc_num && $exp_month && $exp_year && $cc_code && $agree_terms == 'y') {
		if (strlen($phone) < 10  || strlen($phone) > 14) {
			$frmMsg .= 'You must enter a valid phone #.';
			$signup_pass = false;
		}
		if (strlen($zip) < 5) {
			$frmMsg .= 'You must enter a valid zip.';
			$signup_pass = false;
		}
		// Error message
		if ($signup_pass == false) {
			$frmMsg = str_replace('.','.<br />',$frmMsg);
		}
	}
	else {
		$frmMsg = 'Please complete all of the fields.';
		$signup_pass = false;
	}
	// Signup continue
	if ($signup_pass == true) {
		// Update customer shipping address
		$customerObj->updateCustomerAddress($customer_id,'shipping',$shipping_id,$name_first,$name_last,$address,'',$city,$state,$zip,$country,$phone);
		// Order number
		$orders_num = date('HisYmd');
		// Make purchase through Triangle api
		$price = $arrProducts[$product_id][4];
		$shipping = $arrProducts[$product_id][6];
		$total_price = number_format($price+$shipping,2,'.',',');
		// Subscription purchase
		$customFields['trialID'] = $arrProducts[$product_id][2];
		$customFields['planID'] = $arrProducts[$product_id][3];
		$customFields['affiliateID'] = '';
		$triangleObj->triangleMediaPurchase($apiCredentials,'n',$_SESSION['CID'],$orders_num,array(array($arrProducts[$product_id][1],1,$arrProducts[$product_id][0],$price)),$total_price,$total_price,'','','','','','',$billing_name_first,$billing_name_last,$billing_address,'',$billing_city,$billing_state,$billing_zip,$billing_country,$phone,$email,$name_first,$name_last,$address,'',$city,$state,$zip,$country,$phone,$shipping,$cc_type,$cc_num,$exp_month.'/'.$exp_year,$cc_code,$customFields);
		// Order successful
		if ($orderPlaced == true) {
			// Send welcome email
			$emailFile = $scriptPath.'/config/email_templates/cremelumiere/welcome.txt';
			$customerEmail = $email;
			if ($emailFile != false) {
				ob_start();
				include ($emailFile);
				$strMsg = ob_get_contents();
				ob_end_clean();
				// Send customer mail
				$mailObj->sendRawMail($senderName,$senderEmail,$customerEmail,$subject,$strMsg);
			}
			// Set receipt session
			$_SESSION['order_details'] = array('order_total'=>$total_price,'product_name'=>$arrProducts[$product_id][0],'product_price'=>$arrProducts[$product_id][4],'shipping_name'=>$arrProducts[$product_id][5],'shipping_price'=>$arrProducts[$product_id][6],'billing_address'=>$billing_name_first.' '.$billing_name_last.'<br />'.$billing_address.'<br />'.$billing_city.' '.$billing_state.', '.$billing_zip.' '.$billing_country.'<br />'.$phone.'<br />'.$email,'shipping_address'=>$name_first.' '.$name_last.'<br />'.$address.'<br />'.$city.' '.$state.', '.$zip.' '.$country);
			// Clear sessions
			$_SESSION['order_sess'] = false;
			$_SESSION['CID'] = false;
			// Add successful contact to awebber
			//$awebberObj->addContact($name_first,$name_last,$email,'0');
			// Forward to recipe page
			echo '<script language="javascript">window.location = "receipt.php";</script>';
		}
		// Order failed
		else {
			$frmMsg = 'There was a problem processing your order (Card Declined).';
			$signup_pass = false;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><? echo $adminSiteName; ?></title>
<script type="text/javascript" src="js/popups.js"></script>
<script type="text/javascript" language="JavaScript">
if (document.images) {
	<?
	$i = 1;
	foreach ($arrProductsKeys as $key) {
		echo "var product".$i."_off = new Image\n\n";
		echo "product".$i."_off.src = \"images/product".$i."_off.jpg\"\n\n";
		echo "var product".$i."_on = new Image\n\n";
		echo "product".$i."_on.src = \"images/product".$i."_on.jpg\"\n\n";
		$i++;
	}
	?>
}
var active_target = false;

function roll(id,name) {
	if (active_target != id) {
		if (document.images) {
			document.images[id].src=eval(name+".src");
		}
	}
}

function toggleProduct(product_id) {
	<?
	$i = 1;
	foreach ($arrProducts as $key => $val) {
		echo "var arr".$key." = {productImage:\"product".$i."\",productName:\"".$val[0]."\",productPrice:\"".$val[4]."\",shippingName:\"".$val[5]."\",shippingPrice:\"".$val[6]."\"};\n";
		$i++;
	}
	?>
	var image_target = eval("arr"+product_id).productImage;
	active_target = image_target;
	document.getElementById('product_id').value = product_id;
	<?
	$i = 1;
	foreach ($arrProductsKeys as $key) {
		echo "roll('product".$i."','product".$i."_off');\n";
		$i++;
	}
	?>
	//roll(image_target,image_target+'_on');
	//document.getElementById(image_target).src = image_target+'_on';
	document.images[image_target].src=eval(image_target+"_on.src");
	document.getElementById('product_name').innerHTML = eval("arr"+product_id).productName;
	document.getElementById('shipping_name').innerHTML = eval("arr"+product_id).shippingName;
	document.getElementById('shipping_price').innerHTML = eval("arr"+product_id).shippingPrice;
	document.getElementById('product_total').innerHTML = (Number(eval("arr"+product_id).productPrice)+Number(eval("arr"+product_id).shippingPrice)).toFixed(2);
	
}
</script>
<link href="css/payment.css" rel="stylesheet">
<?php include 'templates/sitewide/includes.php'; ?>
</head>
<body>
	<div id="main">
		<?php include 'templates/sitewide/header.php'; ?>
		<?php include 'templates/order/content.php'; ?>
		<div id="orderForm">
			<a name="order-now" id="order-now"></a>
			<form action="order.php#order" method="post" >
				<input type="hidden" name="order" value="y" />
				<input type="hidden" name="order_id" value="<? echo $_SESSION['order_sess']; ?>" />
				<input type="hidden" name="shipping_id" value="<? echo $shipping_id; ?>" />
				<input type="hidden" id="product_id" name="product_id" value="<? echo $product_id; ?>" />
				<div id="billingSame">
					<p>Is your billing address the same as your shipping?</p>
					<label>Yes</label><input id="radioOne" type="radio" name="billing_same_as_shipping" value="1" checked /> 
					<label>No</label><input id="radioTwo" type="radio" name="billing_same_as_shipping" value="0" /> 
					<div class="clearfix"></div>
				</div>
				<? echo ($frmMsg != '') ? '<div class="form_error">'.$frmMsg.'</div>' : ''; ?>
				<div id="customerInfo" style="display:none;">
					<label>First Name</label>
					<input name="name_first" type="text" class="field" id="name_first" value="<? echo $name_first; ?>" />
					<div class="clearfix"></div>
					<label>Last Name</label>
					<input name="name_last" type="text" class="field" id="name_last" value="<? echo $name_last; ?>"  />
					<div class="clearfix"></div>
					<label>Address</label>
					<input name="address" type="text" class="field" id="address" value="<? echo $address; ?>"  />
					<div class="clearfix"></div>
					<label>City</label>
					<input name="city" type="text" class="field" id="city" value="<? echo $city; ?>"  />
					<div class="clearfix"></div>
					<label>State</label>
					<select name="state" id="state" class="field">
						<option value="">- Select -</option>
							  <?
							foreach ($regionArray as $key => $val) {
								echo '<option value="'.$key.'"';
								if ($key == $state) {
									echo ' selected';
								}
								echo '>'.ucwords(strtolower($val)).'</option>';
							}
							?>
					</select>
					<div class="clearfix"></div>
					<label>Zip</label>
					<input name="zip" type="text" class="field" id="zip" value="<? echo $zip; ?>" />
					<div class="clearfix"></div>
					<label>Phone</label>
					<input name="phone" type="text" class="field" id="phone" value="<? echo $phone; ?>" />
					<div class="clearfix"></div>
				</div>					
				<div style="text-align:center; margin:8px 0 0 0;"><img src="images/cc_info.png" alt=""></div>
				<div class="clearfix"></div>
				<label>Card Type</label>
				<select name="cc_type" class="field" id="cc_type">
				  <option value="">Select Card Type</option>
				  <option value="VISA"<? if($cc_type == 'VISA'){echo' selected';} ?>>Visa</option>
				  <option value="MC"<? if($cc_type == 'MC'){echo' selected';} ?>>MasterCard</option>
				  <option value="DC"<? if($cc_type == 'DC'){echo' selected';} ?>>Discover</option>
				  <option value="AMEX"<? if($cc_type == 'AMEX'){echo' selected';} ?>>American Express</option>
				</select>
				<div class="clearfix"></div>
				<label>Card Number</label>
				<input name="cc_num" type="text" class="field" id="cc_num" value="<? echo $cc_num; ?>" />
				<div class="clearfix"></div>
				<label>Card Expiration</label>
				<select name="exp_month" id="exp_month" class="field1">
					<option value="">Month</option>
					  <?
					for ($i=1;$i<=12;$i++) {
						$y = (strlen($i)<2) ? '0'.$i : $i;
						echo '<option value="'.$y.'"';
						if ($y == $exp_month) {
							echo ' selected';
						}
						echo '>'.$arrMonths[$y-1].'</option>';
					}
					?>
				</select>
				<select name="exp_year" id="exp_year" class="field1">
					<option value="">Year</option>
					  <?
					for ($i=(date('Y'));$i<=(date('Y')+8);$i++) {
						echo '<option value="'.$i.'"';
						if ($i == $exp_year) {
							echo ' selected';
						}
						echo '>'.$i.'</option>';
					}
					?>
				</select>
				<div class="clearfix"></div>
				<label>Card CVV</label>
				<input name="cc_code" type="text" class="field2" id="cc_code" style="width:15%;" value="<? echo $cc_code; ?>" />
				<div class="clearfix" style="margin:0 0 20px 0;"></div>
				<input type="checkbox" name="agree_terms" value="y"<? if ($agree_terms == 'y') {echo ' checked';} ?> /> 
				<p>I agree to the website <a href="javascript:popUp('tc.htm')">Terms & Conditions</a></p>
				<div style="text-align:center;">
					<?php include 'templates/order/submit.php'; ?>
				</div>
				<input type="hidden" name="baffle" value="y" />
				<div class="clearfix"></div>
			</form>
			<div class="terms">
				<?php include 'templates/order/terms.php'; ?>
			</div>
		</div>
		<?php include 'templates/order/subcontent.php'; ?>
	</div>
	<?php include 'sitewide/footer.php'; ?>
	<!-- Not sure if we need the below bit-->
	<? echo ($product_id != '') ? '<script language="javascript">toggleProduct(\''.$product_id.'\');</script>' : ''; ?>		
</body>
</html>
