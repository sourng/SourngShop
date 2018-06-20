<div class="container">
                    <div class="product-slider">
                        


                      <?php 
                        
                      foreach ($hotThisWeek as $rows) {                         
                            ?>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="<?php echo site_url(); ?>cats.html/<?php echo $rows['product_cat'].'/'.$rows['product_brand'].'/'.$rows['product_id']; ?>">
                                                <img src="<?php echo base_url(); ?>uploads/product/<?php echo $rows['product_image']; ?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="<?php echo site_url(); ?>cats.html/<?php echo $rows['product_cat'].'/'.$rows['product_brand'].'/'.$rows['product_id']; ?>">
                                                <img src="<?php echo base_url(); ?>uploads/product/<?php echo $rows['product_image1']; ?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo site_url(); ?>cats.html/<?php echo $rows['product_cat'].'/'.$rows['product_brand'].'/'.$rows['product_id']; ?>" class="invisible">
                                    <img src="<?php echo base_url(); ?>uploads/product/<?php echo $rows['product_image2']; ?>" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3><a href="<?php echo site_url(); ?>detail.html/<?php echo $rows['product_id']; ?>"><?php echo $rows['product_title']; ?></a></h3>
                                    <p class="price">

                                        <?php 
                                        if($rows['product_dist']>0){
                                             echo "<del> $". $rows['product_price'] ."</del>" ;
                                         }                                    

                                        ?>

                                        <?php 
                                        // Find Dist Sell
$sellPrice=$rows['product_price']-($rows['product_price']*$rows['product_dist'])/100;
echo "$ ".round($sellPrice,2);

                                         ?>
                                     </p>
                                </div>
                                <!-- /.text -->                              

                                <?php 

                                if($rows['top_sell']!=0){
                                    ?>
                                     <div class="ribbon sale">
                                    <div class="theribbon">SALE</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                    <?php
                                }

                                ?>
                                <!-- /.ribbon -->
                                <?php
                                if($rows['condition'] !=''){
                                    ?>
                                    <div class="ribbon new">
                                    <div class="theribbon"><?php echo $rows['condition']; ?></div>
                                    <div class="ribbon-background"></div>
                                </div>
                                    <?php
                                }
                                 ?>                                
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

                      ?>
                        

                    </div>
                    <!-- /.product-slider -->
                </div>