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
                        <li>Checkout - Order review</li>
                    </ul>
                </div>

                <div class="col-md-9" id="checkout">

                    <div class="box">
                        <form method="post" action="<?php echo site_url(); ?>shopping/save_order">
                            <h1>Checkout - Order review</h1>
                            <ul class="nav nav-pills nav-justified">
                                <li><a href="<?php echo site_url() ?>shopping/checkout1.html"><i class="fa fa-map-marker"></i><br>Address</a>
                                </li>
                                <li><a href="<?php echo site_url() ?>shopping/checkout2.html"><i class="fa fa-truck"></i><br>Delivery Method</a>
                                </li>
                                <li><a href="<?php echo site_url() ?>shopping/checkout3.html"><i class="fa fa-money"></i><br>Payment Method</a>
                                </li>
                                <li class="active"><a href="#"><i class="fa fa-eye"></i><br>Order Review</a>
                                </li>
                            </ul>

                            <div class="content">
                               <div class="box">

                         <!-- <h4>Shopping cart</h4> -->
                            <p class="text-muted">You currently have <?php echo count($this->cart->contents());  ?> item(s) in your cart.</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Product</th>
                                            <th>Quantity</th>
                                            <th colspan="" rowspan="" headers="" scope=""></th>
                                            <th style="width: 120px;" >Unit price</th>
                                            <!-- <th>Discount</th> -->
                                            <th style="width: 180px;" colspan="2">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                      <?php 
                  if(isset($cart) && is_array($cart) && count($cart)){
                  $i=1;
                  foreach ($cart as $data) { 
                    // $cart as $item
                  ?>

                <tr class="item first rowid<?php echo $data['rowid'] ?>">
                  <td class="thumb">
                     <img style="height: 34px;" src="<?php echo base_url(); ?>uploads/product/<?php echo $data['image']; ?>" alt="<?php echo $data['id'];?>">
                  </td>
                  <td style="width: 350px;" class="name"><?php echo $data['name']; ?></td>                  
                  <td class="qnt-count" width="60">
                    <input onchange="javascript:updateproduct('<?php echo $data['rowid'] ?>')" class="quantity qty<?php echo $data['rowid'] ?> form-control" type="number" min="1" value="<?php echo $data['qty'] ?>">                    
                  </td>
                  <td><span class="Update btn btn-info" onclick="javascript:updateproduct('<?php echo $data['rowid'] ?>')">Update</span></td>
                  <td  class="price">$ <span class="price<?php echo $data['rowid'] ?>"><?php echo $data['price'] ?></span></td>
                    <!-- <td>$0.00</td> -->
                  <td style="width: 120px;" class="total">$ <span class="subtotal subtotal<?php echo $data['rowid'] ?>"><?php echo $data['subtotal'] ?></span></td>
                  <td class="delete"><i class="icon-delete btn btn-danger" onclick="javascript:deleteproduct('<?php echo $data['rowid'] ?>')"><i class="fa fa-trash-o"></i></i></td>
                </tr>

<?php
                  $i++;
                    } }
                ?>


                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">Total</th>
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
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->

                            
                     
                    </div>
                    <!-- /.box -->
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.content -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="checkout3.html" class="btn btn-default"><i class="fa fa-chevron-left"></i>Back to Payment method</a>
                                </div>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary">Place an order<i class="fa fa-chevron-right"></i>
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