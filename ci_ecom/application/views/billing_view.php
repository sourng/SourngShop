
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Shopping cart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

</head>
<body>

<div id="bill_info">

		<?php
		$grand_total = 0;
		// Calculate grand total.
		if ($cart = $this->cart->contents()):
		foreach ($cart as $data):
		$grand_total = $grand_total + $data['subtotal'];
		endforeach;
		endif;
		?>       
            <form name="billing" method="post" action="<?php echo site_url('welcome/save_order') ?>" >
            <div align="center">
                <h1 align="center">Billing Info</h1>
                <table border="0" cellpadding="2px">
                    <tr><td>Order Total:</td><td><strong>INR <?php echo $grand_total; ?></strong></td></tr>
                    <tr><td>Your Name:</td><td><input type="text" name="name" required=""/></td></tr>
                    <tr><td>Address:</td><td><input type="text" name="address" required="" /></td></tr>
                    <tr><td>Email:</td><td><input type="text" name="email" required="" /></td></tr>
                    <tr><td>Phone:</td><td><input type="text" name="phone"  required="" /></td></tr>
                    <tr><td><a class ='fg-button teal' id='back' href="<?php echo site_url(); ?>">Back</a></td>
                    	<td><input class ='fg-button teal' type="submit" value="Place Order" /></td>
                    </tr> 
                 
                </table>
            </div>
        </form>
        </div>


</body>
</html>

