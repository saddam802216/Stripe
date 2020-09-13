<?php
/**
 * Stripe - Payment Gateway integration
 * ==============================================================================
 * @version v1.0: stripe-payment-gateway.php
 * @copyright Copyright (c) 2017, http://www.utxinfotech.com
 * @author Saddam Hussain (saddam802216@gmail.com)
 * You are free to use, distribute, and modify this software
 * ==============================================================================
 *
 */

// include Stripe
require 'stripe/Stripe.php';
error_reporting(1);
$params = array(
		"testmode"   => "on",
		"private_live_key" => "sk_live_UUCOI2p5f6ye9oqmIPKgohSW",
		"public_live_key"  => "pk_live_NM71jfG5jXk2ChKV4PnBW6Uw",
		"private_test_key" => "sk_test_nFOQWUpphf0G0UIlC25gQR3P",
		"public_test_key"  => "pk_test_OBsry9sQxUWAFoiOPpTBncXD"
);

if ($params['testmode'] == "on") {
	Stripe::setApiKey($params['private_test_key']);
	$pubkey = $params['public_test_key'];
} else {
	Stripe::setApiKey($params['private_live_key']);
	$pubkey = $params['public_live_key'];
}

if(isset($_POST['stripeToken']))
{
	$amount_cents = str_replace(".","","1.00");  // Chargeble amount
	$invoiceid = "14526321";                      // Invoice ID
	$description = "Invoice #" . $invoiceid . " - " . $invoiceid;

	try {

		$charge = Stripe_Charge::create(array(
						"amount" => $amount_cents,
						"currency" => "usd",
						"source" => $_POST['stripeToken'],
						"description" => $description)
		);

		if ($charge->card->address_zip_check == "fail") {
			throw new Exception("zip_check_invalid");
		} else if ($charge->card->address_line1_check == "fail") {
			throw new Exception("address_check_invalid");
		} else if ($charge->card->cvc_check == "fail") {
			throw new Exception("cvc_check_invalid");
		}
		// Payment has succeeded, no exceptions were thrown or otherwise caught

		$result = "success";

	} catch(Stripe_CardError $e) {

		$error = $e->getMessage();
		$result = "declined";

	} catch (Stripe_InvalidRequestError $e) {
		$result = "declined";
	} catch (Stripe_AuthenticationError $e) {
		$result = "declined";
	} catch (Stripe_ApiConnectionError $e) {
		$result = "declined";
	} catch (Stripe_Error $e) {
		$result = "declined";
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "declined";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "declined";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "declined";
		} else {
			$result = "declined";
		}
	}

	if($result=="success") {
		$response = "<div class='col-sm-offset-3 col-sm-9 text-success'>Your Payment has been processed successfully.</div>";
	} else{
		$response = "<div class='text-danger'>Stripe Payment Status : \".$result.</div>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex">
	<title>Stripe Payment Gateway Using PHP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</head>
<style>

	.stripe-main {
		width: 40%;
		border: solid 1px #ddd;
		margin-top: 30px;
		margin-bottom: 30px;
		padding: 20px;
		box-shadow: 2px 2px 2px #eee;
	}
</style>
<body>
<div class="container stripe-main">
	<div style="margin-top:20px;"></div>
	 <div><img src="Stripe.png" width="250"> <!-- tamkuhionline_main_Blog1_1x1_as --><ins class="adsbygoogle" style="display:block"  data-ad-client="ca-pub-3256637139560604" data-ad-slot="2681327572" data-ad-format="auto"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script></div>
	<hr>
		<form action="" class="form-horizontal" method="POST" id="payment-form">
		<fieldset>
					<div class="form-group">
						<div class="form-group">
							<label class="col-sm-3 control-label" for="accountNumber">Payment Amount</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="name" value="$1.00">
							</div>
						</div>
					</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="accountNumber">Card Number</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" size="20" data-stripe="number" value="4111111111111111" required>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="expirationMonth">Expiration Date</label>
				<div class="col-sm-9">
					<div class="row">
						<div class="col-xs-5">
							<select class="form-control col-sm-3" data-stripe="exp_month" required>
								<option>Month</option>
								<option value="01">Jan (01)</option>
								<option value="02">Feb (02)</option>
								<option value="03">Mar (03)</option>
								<option value="04">Apr (04)</option>
								<option value="05">May (05)</option>
								<option value="06">June (06)</option>
								<option value="07">July (07)</option>
								<option value="08">Aug (08)</option>
								<option value="09">Sep (09)</option>
								<option value="10">Oct (10)</option>
								<option value="11">Nov (11)</option>
								<option value="12" selected="">Dec (12)</option>
							</select>
						</div>
						<div class="col-xs-3">
							<select class="form-control" data-stripe="exp_year">
								<option value="17">2017</option>
								<option value="18">2018</option>
								<option value="19">2019</option>
								<option value="20" selected="">2020</option>
								<option value="21">2021</option>
								<option value="22">2022</option>
								<option value="23">2023</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="cvNumber">Card CVV</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" data-stripe="cvc" value="123">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" name="pay" id="pay" class="btn btn-primary">Pay Now</button>
				</div>
				<?php if(isset($response)){echo $response;} ?> <div class='col-sm-offset-3 col-sm-9  text-danger payment-errors'></div>
			</div>
		</fieldset>
	</form>
<!-- tamkuhionline_main_Blog1_1x1_as --><ins class="adsbygoogle" style="display:block"  data-ad-client="ca-pub-3256637139560604" data-ad-slot="2681327572" data-ad-format="auto"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<!-- TO DO : Place below JS code in js file and include that JS file -->
<script type="text/javascript">
	Stripe.setPublishableKey('<?php echo $params['public_test_key']; ?>');

	$(function() {
		var $form = $('#payment-form');
		$form.submit(function(event) {
			// Disable the submit button to prevent repeated clicks:
			$form.find('.submit').prop('disabled', true);

			// Request a token from Stripe:
			Stripe.card.createToken($form, stripeResponseHandler);

			// Prevent the form from being submitted:
			return false;
		});
	});

	function stripeResponseHandler(status, response) {
		// Grab the form:
		var $form = $('#payment-form');

		if (response.error) { // Problem!

			// Show the errors on the form:
			$form.find('.payment-errors').text(response.error.message);
			$form.find('.submit').prop('disabled', false); // Re-enable submission

		} else { // Token was created!

			// Get the token ID:
			var token = response.id;

			// Insert the token ID into the form so it gets submitted to the server:
			$form.append($('<input type="hidden" name="stripeToken">').val(token));

			// Submit the form:
			$form.get(0).submit();
		}
	};
</script>
</body>
</html>