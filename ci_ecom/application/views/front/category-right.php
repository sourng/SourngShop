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
        Obaju : e-commerce template
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

    <link rel="<?php echo base_url(); ?>template/shortcut icon" href="favicon.png">



</head>

<body>
   
    <!-- *** TOPBAR ***
 _________________________________________________________ -->
    <?php $this->load->view('inc/topheader');  ?>
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
                        <li>Ladies</li>
                    </ul>
                </div>

                <div class="col-md-9">

                    <div class="box">
                        <h1>Ladies</h1>
                        <p>In our Ladies department we offer wide selection of the best products we have found and carefully selected worldwide.</p>
                    </div>

                    <div class="box info-bar">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 products-showing">
                                Showing <strong>12</strong> of <strong>25</strong> products
                            </div>

                            <div class="col-sm-12 col-md-8  products-number-sort">
                                <div class="row">
                                    <form class="form-inline">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-number">
                                                <strong>Show</strong>  
                                                <a href="#" class="btn btn-default btn-sm btn-primary">12</a>  <a href="#" class="btn btn-default btn-sm">24</a>  <a href="#" class="btn btn-default btn-sm">All</a> products
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-sort-by">
                                                <strong>Sort by</strong>
                                                <select name="sort-by" class="form-control">
                                                    <option>Price</option>
                                                    <option>Name</option>
                                                    <option>Sales first</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row products">

                        <?php 
                            for ($i=0; $i <3 ; $i++) { 
                               if($i%2==0){
                                ?>
                                <div class="col-md-4 col-sm-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="detail.html">
                                                <img src="<?php echo base_url();?>uploads/product/product1.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="detail.html">
                                                <img src="<?php echo base_url();?>uploads/product/product1_2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="detail.html" class="invisible">
                                    <img src="<?php echo base_url();?>uploads/product/product1.jpg" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3><a href="detail.html">Fur coat with very but very very long name</a></h3>
                                    <p class="price">$143.00</p>
                                    <p class="buttons">
                                        <a href="detail.html" class="btn btn-default">View detail</a>
                                        <a href="basket.html" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </p>
                                </div>
                                <!-- /.text -->
                            </div>
                            <!-- /.product -->
                        </div>



                                <?php
                               }else{
                                ?>
                                  <div class="col-md-4 col-sm-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="detail.html">
                                                <img src="<?php echo base_url();?>uploads/product/product2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="detail.html">
                                                <img src="<?php echo base_url();?>uploads/product/product2_2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="detail.html" class="invisible">
                                    <img src="<?php echo base_url();?>uploads/product/product2.jpg" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3><a href="detail.html">White Blouse Armani</a></h3>
                                    <p class="price"><del>$280</del> $143.00</p>
                                    <p class="buttons">
                                        <a href="detail.html" class="btn btn-default">View detail</a>
                                        <a href="basket.html" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </p>
                                </div>
                                <!-- /.text -->

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

                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon -->
                            </div>
                            <!-- /.product -->
                        </div>

                                <?php
                               }
                            }


                        ?>                   

                       


                        <!-- /.col-md-4 -->
                    </div>
                    <!-- /.products -->

                    <div class="pages">

                        <p class="loadMore">
                            <a href="#" class="btn btn-primary btn-lg"><i class="fa fa-chevron-down"></i> Load more</a>
                        </p>

                        <ul class="pagination">
                            <li><a href="#">&laquo;</a>
                            </li>
                            <li class="active"><a href="#">1</a>
                            </li>
                            <li><a href="#">2</a>
                            </li>
                            <li><a href="#">3</a>
                            </li>
                            <li><a href="#">4</a>
                            </li>
                            <li><a href="#">5</a>
                            </li>
                            <li><a href="#">&raquo;</a>
                            </li>
                        </ul>
                    </div>


                </div>
                <!-- /.col-md-9 -->

                <?php $this->load->view('inc/right-sidebar'); ?>

            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->


        <!-- *** FOOTER ***
 _________________________________________________________ -->
         <!-- *** FOOTER ***
 _________________________________________________________ -->
       <?php $this->load->view('inc/footer'); ?>

        <!-- *** FOOTER END *** -->




        <!-- *** COPYRIGHT ***
 _________________________________________________________ -->
       <?php $this->load->view('inc/copyright'); ?>
        <!-- *** COPYRIGHT END *** -->
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





</body>

</html>