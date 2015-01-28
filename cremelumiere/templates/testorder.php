<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><? echo $adminSiteName; ?></title>
<script type="text/javascript" src="../js/popups.js"></script>
<?php include 'sitewide/includes.php'; ?>
<link href="../css/payment.css" rel="stylesheet">
</head>
<body>
		<?php include 'sitewide/header.php'; ?>
		<?php include 'order/content.php'; ?>
		<div id="orderForm">
			<a name="order-now" id="order-now"></a>
			<form action="../order.php#order" method="post" >
				<input type="hidden" name="order" value="y" />
				<input type="hidden" name="order_id" value="<? echo $_SESSION['order_sess']; ?>" />
				<input type="hidden" name="shipping_id" value="<? echo $shipping_id; ?>" />
				<input type="hidden" id="product_id" name="product_id" value="<? echo $product_id; ?>" />
				<!--<? echo ($frmMsg != '') ? '<span class="form_error">'.$frmMsg.'</span>' : ''; ?>-->
				<div id="billingSame">
					<p>Is your billing address the same as your shipping?</p>
					<label>Yes</label><input id="radioOne" type="radio" name="billing_same_as_shipping" value="1" checked /> 
					<label>No</label><input id="radioTwo" type="radio" name="billing_same_as_shipping" value="0" /> 
					<div class="clearfix"></div>
				</div>
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
				<div style="text-align:center; margin:8px 0 0 0;"><img src="../images/cc_info.png" alt=""></div>
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
					<?php include 'order/submit.php'; ?>
				</div>
				<input type="hidden" name="baffle" value="y" />
				<div class="clearfix"></div>
			</form>
			<div class="terms">
				<?php include 'order/terms.php'; ?>
			</div>
		</div>
		<?php include 'order/subcontent.php'; ?>
	</div>
	<?php include 'sitewide/footer.php'; ?>
</body>
</html>
