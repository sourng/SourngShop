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
        <?php echo $getProductDetail[0]['product_title']; ?>
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
                        <li><a href="<?php echo site_url(); ?>">Home</a>
                        </li>
                        <li><a href="<?php echo site_url(); ?>#">
                            <?php
                            $brand_id=$getProductDetail[0]['product_brand'];
                             $brand=$this->mcrud->get_by_sql("SELECT brand_title FROM brands WHERE brand_id= $brand_id");
                            echo $brand[0]['brand_title'];
                            ?>

                           </a>
                        </li>
                        <li><a href="<?php echo site_url(); ?>#"><?php
                            $cat_id=$getProductDetail[0]['product_cat'];
                             $cat=$this->mcrud->get_by_sql("SELECT cat_title FROM categories WHERE cat_id= $cat_id");
                            echo $cat[0]['cat_title'];
                            ?></a>
                        </li>
                        <li><?php echo $getProductDetail[0]['product_title']; ?></li>
                    </ul>

                </div>

                <div class="col-md-3">
                    <!-- *** MENUS AND FILTERS ***
 _________________________________________________________ -->
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Categories</h3>
                        </div>

                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">

                                <?php 

                                    foreach ($brandLeft as $row) {
                                        ?>
                                        <li class="active">
                                    <a href="<?php echo site_url();?>category.html"><?php echo $row['brand_title']; ?> <span class="badge pull-right"><?php echo $row['brand_count']; ?></span></a>
                                    
                                    <ul>

                                        <?php 
                                        foreach ($catLetf as $rows) {
                                            if($row['product_brand']==$rows['product_brand']){
                                           ?>
                                            <li><a href="<?php echo site_url();?>category.html"><?php echo $rows['cat_title']; ?> <span class="badge pull-right"><?php echo $rows['cat_count']; ?></span></a>
                                        </li>

                                           <?php
                                            }
                                        }
                                        ?>                                       
                                        
                                    </ul>

                                </li><?php
                                    }

                                ?>




                                

                            </ul>

                        </div>
                    </div>

                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Brands <a class="btn btn-xs btn-danger pull-right" href="#"><i class="fa fa-times-circle"></i> Clear</a></h3>
                        </div>

                        <div class="panel-body">

                            <form>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox">Armani (10)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox">Versace (12)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox">Carlo Bruni (15)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox">Jack Honey (14)
                                        </label>
                                    </div>
                                </div>

                                <button class="btn btn-default btn-sm btn-primary"><i class="fa fa-pencil"></i> Apply</button>

                            </form>

                        </div>
                    </div>

                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Colours <a class="btn btn-xs btn-danger pull-right" href="#"><i class="fa fa-times-circle"></i> Clear</a></h3>
                        </div>

                        <div class="panel-body">

                            <form>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> <span class="colour white"></span> White (14)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> <span class="colour blue"></span> Blue (10)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> <span class="colour green"></span> Green (20)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> <span class="colour yellow"></span> Yellow (13)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> <span class="colour red"></span> Red (10)
                                        </label>
                                    </div>
                                </div>

                                <button class="btn btn-default btn-sm btn-primary"><i class="fa fa-pencil"></i> Apply</button>

                            </form>

                        </div>
                    </div>

                    <!-- *** MENUS AND FILTERS END *** -->

                    <div class="banner">
                        <a href="#">
                            <img src="<?php echo base_url();?>uploads/product/banner.jpg" alt="sales 2014" class="img-responsive">
                        </a>
                    </div>
                </div>

                <div class="col-md-9">
                    <?php 
                    $id=$getProductDetail[0]['product_id'];
                    $name=$getProductDetail[0]['product_title'];
                    $description=$getProductDetail[0]['product_desc'];

                    $sellPrice=$getProductDetail[0]['product_price']-($getProductDetail[0]['product_price']*$getProductDetail[0]['product_dist'])/100;
                               
                    $price=round($sellPrice,2);

                    $img=$getProductDetail[0]['product_image'];

                    ?>


                    <form id="detailCartForm" action="<?php echo site_url(); ?>shopping/add" method="post" accept-charset="utf-8">
                        <input type="hidden" name="id" id="<?php echo $id; ?>" value="<?php echo $id; ?>">
                        <input type="hidden" name="name" value="<?php echo $name; ?>">
                        <input type="hidden" name="description" value="<?php echo $description; ?>">
                        <input type="hidden" name="price" value="<?php echo $price; ?>">
                        <input type="hidden" name="image" value="<?php echo $img; ?>">

                        <div class="row" id="productMain">
                        <div class="col-sm-6">
                            <div id="mainImage">
                                <img src="<?php echo base_url();?>uploads/product/<?php echo $img; ?>" alt="" class="img-responsive">
                            </div>

                            <div class="ribbon sale">
                                <div class="theribbon">SALE</div>
                                <div class="ribbon-background"></div>
                            </div>
                            <!-- /.ribbon -->

                            <div class="ribbon new">
                                <div class="theribbon">NEW</div>
                                <div class="ribbon-background"></div>
                            </div>
                            <!-- /.ribbon -->

                        </div>
                        <div class="col-sm-6">
                            <div class="box">
                                <h1 class="text-center"><?php echo $getProductDetail[0]['product_title']; ?></h1>
                                <p class="goToDescription"><a href="#details" class="scroll-to">Scroll to product details, material & care and sizing</a>
                                </p>
                                <p class="price"><?php 
                                $sellPrice=$getProductDetail[0]['product_price']-($getProductDetail[0]['product_price']*$getProductDetail[0]['product_dist'])/100;
                                echo "$ ".round($sellPrice,2);
                                ?>

                               </p>

                                <p class="text-center buttons">
                                   <!--  <a href="<?php echo site_url(); ?>shopping/cart" id="add_button"  class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add to cart</a>  -->
                                    
                                    <button type="submit" class="btn btn-primary" name="action"><i class="fa fa-shopping-cart"></i> Add to Cart</button>

                                        <!--  <button type="button" name="add_cart" class="btn btn-success add_cart" data-productname="'.$name.'" data-price="'.$price.'" data-productid="'.$id.'" /><i class="fa fa-shopping-cart"></i> Add to Cart</button> -->

                                    <a href="basket.html" class="btn btn-default"><i class="fa fa-heart"></i> Add to wishlist</a>
                                </p>




                            </div>

                            <div class="row" id="thumbs">
                                <div class="col-xs-4">
                                    <a href="<?php echo base_url();?>uploads/product/<?php echo $getProductDetail[0]['product_image']; ?>" class="thumb">
                                        <img src="<?php echo base_url();?>uploads/product/<?php echo $getProductDetail[0]['product_image']; ?>" alt="" class="img-responsive">
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="<?php echo base_url();?>uploads/product/<?php echo $getProductDetail[0]['product_image1']; ?>" class="thumb">
                                        <img src="<?php echo base_url();?>uploads/product/<?php echo $getProductDetail[0]['product_image1']; ?>" alt="" class="img-responsive">
                                    </a>
                                </div>
                                <div class="col-xs-4">
                                    <a href="<?php echo base_url();?>uploads/product/<?php echo $getProductDetail[0]['product_image2']; ?>" class="thumb">
                                        <img src="<?php echo base_url();?>uploads/product/<?php echo $getProductDetail[0]['product_image2']; ?>" alt="" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="box" id="details">
                        <?php echo $getProductDetail[0]['product_desc']; ?>
                    </div> 
                        
                    </form>

                    




                    <!-- End Detail -->



                    <div class="row same-height-row">
                        <div class="col-md-3 col-sm-6">
                            <div class="box same-height">
                                <h3>You may also like these products</h3>
                            </div>
                        </div>

                        <?php 
                        foreach ($otherProducts as $row) {
                           
                           ?>

                            <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="<?php echo site_url(); ?>cats.html/<?php echo $row['product_cat'].'/'.$row['product_brand'].'/'.$row['product_id']; ?>">
                                                <img src="<?php echo base_url();?>uploads/product/<?php echo $row['product_image1']; ?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="<?php echo site_url(); ?>cats.html/<?php echo $row['product_cat'].'/'.$row['product_brand'].'/'.$row['product_id']; ?>">
                                                <img src="<?php echo base_url();?>uploads/product/<?php echo $row['product_image2']; ?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo site_url(); ?>cats.html/<?php echo $row['product_cat'].'/'.$row['product_brand'].'/'.$row['product_id']; ?>" class="invisible">
                                    <img src="<?php echo base_url();?>uploads/product/<?php echo $row['product_image3']; ?>" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3><?php echo $row['product_title']; ?></h3>
                                    <p class="price"><?php                                     
                                     $sellPrice=$row['product_price']-($row['product_price']*$row['product_dist'])/100;
                                echo "$ ".round($sellPrice,2);


                                    ?></p>
                                </div>
                            </div>
                            <!-- /.product -->
                        </div>


                           <?php

                        }

                        ?>                    

                       

                    </div>



                </div>
                <!-- /.col-md-9 -->
            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->


        <!-- *** FOOTER ***
 _________________________________________________________ -->
      <?php $this->load->view('inc/footer'); ?>

        <!-- *** FOOTER END *** -->




        <!-- *** COPYRIGHT ***
 _________________________________________________________ -->
 <?php $this->load->view('inc/copyright'); ?>
        <!-- *** COPYRIGHT END *** -->



    </div>
    <!-- /#all -->


    

    <!-- *** SCRIPTS TO INCLUDE ***
 _________________________________________________________ -->
    <!-- <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap-hover-dropdown.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/front.js"></script> -->

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



     function opencart()
  {
      $.ajax({
                  type: "POST",
                  url: "<?php echo site_url('front/opencart');?>",
                  data: "",
                  success: function (response) {
                  $(".displaycontent").html(response);
                  }
              });
  }

</script>


<div class="modal fade bs-example-modal-lg displaycontent" id="exampleModal" tabindex="-1" >

</body>

</body>

</html>