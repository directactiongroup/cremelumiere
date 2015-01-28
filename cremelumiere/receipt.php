<?
// Site-specific config file
$siteConfig = 'cremelumiere';

// Bootstrap
$scriptPath = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/cremelumiere'));
require_once($scriptPath.'/app/bootstrap.php');

//10-23-14 - below 2 lines were uncommented for testing
error_reporting(-1);
ini_set('display_errors',1);

// Define variables
$page = (isset($_REQUEST['page'])) ? $cleanObj->cleanAlphaLower($_REQUEST['page']) : false;

// Write no-cache headers
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

// Check order session
if (!isset($_SESSION['order_details']) || empty($_SESSION['order_details'])) {
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><? echo $adminSiteName; ?></title>
    <link href="css/style.css" rel="stylesheet">
	<link href="css/payment.css" rel="stylesheet">
	<script type="text/javascript" src="js/popups.js"></script>
	<?php include 'templates/sitewide/includes.php'; ?>
  </head>

  <body>
  	<div id="main">	
		<?php include 'templates/sitewide/header.php'; ?>
		<?php include 'templates/receipt/content.php'; ?>
		<h2><span class="green">Receipt</span> Please Save for your Records</h2>
		<h4>Products Ordered:</h4>
		<p><? echo $_SESSION['order_details']['product_name']; ?><br />
		Price: <span class="price">$<? echo $_SESSION['order_details']['product_price']; ?></span><br />
		Shipping method: <? echo $_SESSION['order_details']['shipping_name']; ?> <span class="price"><? echo $_SESSION['order_details']['shipping_price']; ?></span></p>
		<h4 class="total">Total <span class="price">$<? echo $_SESSION['order_details']['order_total']; ?></span></h4>
		<h4 class="total">Billing Address</h4>
		<p>
			<? echo $_SESSION['order_details']['billing_address']; ?>
		</p>
		<h4>Shipping Address</h4>
		<p>
			<? echo $_SESSION['order_details']['shipping_address']; ?>
		</p>
		<div class="clearfix"></div>
		<?php include 'templates/receipt/subcontent.php'; ?>
	</div>
	<?php include 'templates/receipt/footer.php'; ?>
  </body>
</html>
