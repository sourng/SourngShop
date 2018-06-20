<?php 
echo "<div class='col-md-2'>";
    // cart item settings
    $cart_item->user_id=1; // we default to a user with ID "1" for now
    $cart_item->product_id=$id;
 
    // if product was already added in the cart
    if($cart_item->exists()){
        echo "<div class='m-b-10px'>This product is already in your cart.</div>";
        echo "<a href='cart.php' class='btn btn-success w-100-pct'>";
            echo "Update Cart";
        echo "</a>";
    }
 
    // if product was not added to the cart yet
    else{ 
        echo "<form class='add-to-cart-form'>";
            // product id
            echo "<div class='product-id display-none'>{$id}</div>";
 
            // select quantity
            echo "<div class='m-b-10px f-w-b'>Quantity:</div>";
            echo "<input type='number' class='form-control m-b-10px cart-quantity' value='1' min='1' />";
 
            // enable add to cart button
            echo "<button style='width:100%;' type='submit' class='btn btn-primary add-to-cart m-b-10px'>";
                echo "<span class='glyphicon glyphicon-shopping-cart'></span> Add to cart";
            echo "</button>"; 
        echo "</form>";
    }
echo "</div>";
