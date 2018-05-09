<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(1, "mi_brands", $Language->MenuPhrase("1", "MenuText"), "brandslist.php", -1, "", IsLoggedIn() || AllowListMenu('{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}brands'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_cart", $Language->MenuPhrase("2", "MenuText"), "cartlist.php", -1, "", IsLoggedIn() || AllowListMenu('{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}cart'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_categories", $Language->MenuPhrase("3", "MenuText"), "categorieslist.php", -1, "", IsLoggedIn() || AllowListMenu('{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}categories'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_orders", $Language->MenuPhrase("4", "MenuText"), "orderslist.php", -1, "", IsLoggedIn() || AllowListMenu('{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}orders'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(5, "mi_products", $Language->MenuPhrase("5", "MenuText"), "productslist.php", -1, "", IsLoggedIn() || AllowListMenu('{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}products'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(6, "mi_user_info", $Language->MenuPhrase("6", "MenuText"), "user_infolist.php", -1, "", IsLoggedIn() || AllowListMenu('{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}user_info'), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
