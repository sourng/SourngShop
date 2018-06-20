
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Shopping cart details</h4>
      </div>
      <div class="modal-content">

        <div class="box">

                        <form method="post" action="<?php echo site_url(); ?>shopping/cart_view">

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
                                            <th colspan="2">$<span class="grandtotal">0</span></th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="<?php echo site_url(); ?>front" class="btn btn-default"><i class="fa fa-chevron-left"></i> Continue shopping</a>
                                </div>
                                <div class="pull-right">
                                  <button type="button" class="btn btn-warning" onclick="javascript:deleteproduct('all')">Clear Cart</button>
                                    <!-- <a class="btn btn-default" class="Update" onclick="javascript:updateproduct('<?php echo $data['rowid'] ?>')"><i class="fa fa-refresh"></i> Update basket</a> -->
                                    <button type="submit" class="btn btn-primary">Proceed to checkout <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
    

      



<script type="text/javascript">
function deleteproduct(rowid)
{
var answer = confirm ("Are you sure you want to delete?");
if (answer)
{
        $.ajax({
                type: "POST",
                url: "<?php echo site_url('shopping/remove');?>",
                data: "rowid="+rowid,
                success: function (response) {
                    $(".rowid"+rowid).remove(".rowid"+rowid); 
                    $(".cartcount").text(response);  
                    var total = 0;
                    $('.subtotal').each(function(){
                        total += parseFloat($(this).text());
                        $('.grandtotal').text(total);
                    });              
                }
            });
      }
}

var total = 0;
$('.subtotal').each(function(){
    total += parseFloat($(this).text());
    $('.grandtotal').text(total);
});


function updateproduct(rowid)
{
var qty = $('.qty'+rowid).val();
var price = $('.price'+rowid).text();
var subtotal = $('.subtotal'+rowid).text();
    $.ajax({
            type: "POST",
            url: "<?php echo site_url('shopping/update_cart');?>",
            data: "rowid="+rowid+"&qty="+qty+"&price="+price+"&subtotal="+subtotal,
            success: function (response) {
                    $('.subtotal'+rowid).text(response);
                    var total = 0;
                    $('.subtotal').each(function(){
                        total += parseFloat($(this).text());
                        $('.grandtotal').text(total);
                    });     
            }
        });
}


</script>