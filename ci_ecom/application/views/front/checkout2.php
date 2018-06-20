<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Obaju e-commerce template">
    <meta name="author" content="Ondrej Svestka | ondrejsvestka.cz">
    <meta name="keywords" content="">

    <title>
        <?php echo "View Cart"; //$getProductDetail[0]['product_title']; ?>
    </title>

    <meta name="keywords" content="">

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100' rel='stylesheet' type='text/css'>
<!-- styles -->
<link href="<?php echo base_url(); ?>template/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>template/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>template/css/animate.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>template/css/owl.carousel.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>template/css/owl.theme.css" rel="stylesheet">

    <!-- theme stylesheet -->
    <link href="<?php echo base_url(); ?>template/css/style.default.css" rel="stylesheet" id="theme-stylesheet">

    <!-- your stylesheet with modifications -->
    <link href="<?php echo base_url(); ?>template/css/custom.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>template/js/respond.min.js"></script>

    <link rel="shortcut icon" href="<?php echo base_url(); ?>template/favicon.png">


<script type="text/javascript">
// To conform clear all data in cart.
function clear_cart() {
var result = confirm('Are you sure want to clear all bookings?');

if (result) {
window.location = "<?php echo base_url(); ?>index.php/shopping/remove/all";
} else {
return false; // cancel button
}
}
</script>


</head>

<body>
    <!-- *** TOPBAR ***
 _________________________________________________________ -->
  <?php $this->load->view('inc/topheader'); ?>

    <!-- *** TOP BAR END *** -->

    <!-- *** NAVBAR ***
 _________________________________________________________ -->
<?php $this->load->view('inc/navbar'); ?>

    <!-- *** NAVBAR END *** -->
    

    <div id="all">

          <div id="content">
            <div class="container">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a>
                        </li>
                        <li>Checkout - Delivery method</li>
                    </ul>
                </div>

                <div class="col-md-9" id="checkout">

                    <div class="box">
                        <form method="post" action="<?php echo site_url(); ?>shopping/checkout3">
                            <h1>Checkout - Delivery method</h1>
                            <ul class="nav nav-pills nav-justified">
                                <li><a href="<?php echo site_url(); ?>shopping/checkout1"><i class="fa fa-map-marker"></i><br>Address</a>
                                </li>
                                <li class="active"><a href="#"><i class="fa fa-truck"></i><br>Delivery Method</a>
                                </li>
                                <li class="disabled"><a href="#"><i class="fa fa-money"></i><br>Payment Method</a>
                                </li>
                                <li class="disabled"><a href="#"><i class="fa fa-eye"></i><br>Order Review</a>
                                </li>
                            </ul>

                            <div class="content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="box shipping-method">

                                            <h4>USPS Next Day</h4>

                                            <p>Get it right on next day - fastest option possible.</p>

                                            <div class="box-footer text-center">

                                                <input type="radio" name="delivery" value="delivery1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="box shipping-method">

                                            <h4>USPS Next Day</h4>

                                            <p>Get it right on next day - fastest option possible.</p>

                                            <div class="box-footer text-center">

                                                <input type="radio" name="delivery" value="delivery2">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="box shipping-method">

                                            <h4>USPS Next Day</h4>

                                            <p>Get it right on next day - fastest option possible.</p>

                                            <div class="box-footer text-center">

                                                <input type="radio" name="delivery" value="delivery3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->

                            </div>
                            <!-- /.content -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="basket.html" class="btn btn-default"><i class="fa fa-chevron-left"></i>Back to Addresses</a>
                                </div>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary">Continue to Payment Method<i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->


                </div>
                <!-- /.col-md-9 -->

                <div class="col-md-3">

                    <div class="box" id="order-summary">
                        <div class="box-header">
                            <h3>Order summary</h3>
                        </div>
                        <p class="text-muted">Shipping and additional costs are calculated based on the values you have entered.</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Order subtotal</td>
                                        <th colspan="2">$<span class="grandtotal">  <?php
        $grand_total = 0;
        // Calculate grand total.
        if ($cart = $this->cart->contents()):
        foreach ($cart as $data):
        $grand_total = $grand_total + $data['subtotal'];
        endforeach;
        endif;
        echo $grand_total;
        ?> </span></th>
                                    </tr>
                                    <tr>
                                        <td>Shipping and handling</td>
                                        <th>$10.00</th>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <th>$0.00</th>
                                    </tr>
                                    <tr class="total">
                                        <td>Total</td>
                                        <th colspan="2">$<span class="grandtotal"><?php
        $grand_total = 0;
        // Calculate grand total.
        if ($cart = $this->cart->contents()):
        foreach ($cart as $data):
        $grand_total = $grand_total + $data['subtotal'];
        endforeach;
        endif;
        echo $grand_total;
        ?> </span></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <!-- /.col-md-3 -->

            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->


        <!-- *** FOOTER ***
 _________________________________________________________ -->
       <?php $this->load->view('inc/footer'); ?>
        <!-- /#footer -->

        <!-- *** FOOTER END *** -->




        <!-- *** COPYRIGHT ***
 _________________________________________________________ -->
        <div id="copyright">
            <div class="container">
                <div class="col-md-6">
                    <p class="pull-left">© 2015 Your name goes here.</p>

                </div>
                <div class="col-md-6">
                    <p class="pull-right">Template by <a href="https://bootstrapious.com/e-commerce-templates">Bootstrapious</a> & <a href="https://fity.cz">Fity</a>
                         <!-- Not removing these links is part of the license conditions of the template. Thanks for understanding :) If you want to use the template without the attribution links, you can do so after supporting further themes development at https://bootstrapious.com/donate  -->
                    </p>
                </div>
            </div>
        </div>
        <!-- *** COPYRIGHT END *** -->



    </div>
    <!-- /#all -->


    

    <!-- *** SCRIPTS TO INCLUDE ***
 _________________________________________________________ -->
     <script src="<?php echo base_url(); ?>template/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo base_url(); ?>template/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>template/js/jquery.cookie.js"></script>
    <script src="<?php echo base_url(); ?>template/js/waypoints.min.js"></script>
    <script src="<?php echo base_url(); ?>template/js/modernizr.js"></script>
    <script src="<?php echo base_url(); ?>template/js/bootstrap-hover-dropdown.js"></script>
    <script src="<?php echo base_url(); ?>template/js/owl.carousel.min.js"></script>
    <script src="<?php echo base_url(); ?>template/js/front.js"></script>





<script type="text/javascript">
    var frm = $('#detailCartForm');

    frm.submit(function (e) {

        e.preventDefault();
        
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
                console.log('Submission was successful.');
                console.log(data);
                $(".cartcount").text(data);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
                $(".cartcount").text(data);
            },
        });
    });



  //    function opencart()
  // {
  //     $.ajax({
  //                 type: "POST",
  //                 url: "<?php echo site_url('front/opencart');?>",
  //                 data: "",
  //                 success: function (response) {
  //                 $(".displaycontent").html(response);
  //                 }
  //             });
  // }

</script>


<!-- <div class="modal fade bs-example-modal-lg displaycontent" id="exampleModal" tabindex="-1" > -->

</body>

</body>

</html>