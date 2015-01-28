<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><? echo $adminSiteName; ?></title>
	<script type="text/javascript" src="../js/popups.js"></script>
	<?php include 'sitewide/includes.php'; ?>
	<link href="../css/payment.css" rel="stylesheet">
  </head>

  <body>
		<?php include 'sitewide/header.php'; ?>
		<?php include 'receipt/content.php'; ?>
		<h2><span class="purple bold">Receipt</span> Please Save for your Records</h2>
		<h3>Products Ordered:</h3>
		<p><? echo $_SESSION['order_details']['product_name']; ?><br />
		Price: <span class="price">$<? echo $_SESSION['order_details']['product_price']; ?></span><br />
		Shipping method: <? echo $_SESSION['order_details']['shipping_name']; ?> <span class="price"><? echo $_SESSION['order_details']['shipping_price']; ?></span></p>
		<h3 class="total">Total <span class="price">$<? echo $_SESSION['order_details']['order_total']; ?></span></h3>
		<h3 class="total">Billing Address</h3>
		<p>
			<? echo $_SESSION['order_details']['billing_address']; ?>
		</p>
		<h3>Shipping Address</h3>
		<p>
			<? echo $_SESSION['order_details']['shipping_address']; ?>
		</p>
		<div class="clearfix"></div>
		<?php include 'receipt/subcontent.php'; ?>
	</div>
	<?php include 'sitewide/footer.php'; ?>
  </body>
</html>
