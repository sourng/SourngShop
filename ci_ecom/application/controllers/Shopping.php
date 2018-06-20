<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shopping extends CI_Controller {

public function __construct()
{
parent::__construct();
//Load Library and model.
$this->load->library('cart');
$this->load->model('billing_model');
}

public function index()
{
//Get all data from database
$data['products'] = $this->billing_model->get_all();
//send all product data to "shopping_view", which fetch from database.
$this->load->view('shopping_view', $data);
}

function add()
{
// Set array for send data.
$insert_data = array(
'id' => $this->input->post('id'),
'name' => $this->input->post('name'),
'price' => $this->input->post('price'),
'image' => $this->input->post('image'),
'qty' => 1
);

// This function add items into cart.
$this->cart->insert($insert_data);

echo $fefe = count($this->cart->contents());

// This will show insert data in cart.
// redirect('shopping');
}

// function remove($rowid) {
// // Check rowid value.
// if ($rowid==="all"){
// // Destroy data which store in session.
// $this->cart->destroy();
// }else{
// // Destroy selected rowid in session.
// $data = array(
// 'rowid' => $rowid,
// 'qty' => 0
// );
// // Update cart data, after cancel.
// $this->cart->update($data);
// }

// // This will show cancel data in cart.
// redirect('shopping');
// }


function remove() {
	$rowid = $this->input->post('rowid');
	// Check rowid value.
	if ($rowid==="all"){
	// Destroy data which store in session.
		$this->cart->destroy();
	}else{
	// Destroy selected rowid in session.
	$data = array(
			'rowid' => $rowid,
			'qty' => 0
			);
	// Update cart data, after cancel.
	$this->cart->update($data);
	}

	echo $fefe = count($this->cart->contents());
	
	// This will show cancel data in cart.
	}



// function update_cart(){

// // Recieve post values,calcute them and update
// $cart_info = $_POST['cart'] ;
// foreach( $cart_info as $id => $cart)
// {
// $rowid = $cart['rowid'];
// $price = $cart['price'];
// $amount = $price * $cart['qty'];
// $qty = $cart['qty'];

// $data = array(
// 'rowid' => $rowid,
// 'price' => $price,
// 'amount' => $amount,
// 'qty' => $qty
// );

// $this->cart->update($data);
// }
// redirect('shopping');
// }



	function update_cart(){
	// Recieve post values,calcute them and update
	$rowid = $_POST['rowid'];
	$price = $_POST['price'];
	$amount = $price * $_POST['qty'];
	$qty = $_POST['qty'];

	$data = array(
		'rowid' => $rowid,
		'price' => $price,
		'amount' => $amount,
		'qty' => $qty
		);
	$this->cart->update($data);
	echo $data['amount'];
	}
	

function billing_view(){
// Load "billing_view".
	$data=array();


$this->load->view('billing_view');
}

function cart_view(){
// Load "billing_view".
	$data=array();
$data['cart']  = $this->cart->contents();
	
$this->load->view('front/view_cart',$data);
}


function checkout1(){
// Load "billing_view".
	$data=array();

$data['cart']  = $this->cart->contents();

$this->load->view('front/checkout1',$data);
}

function checkout2(){
// Load "billing_view".
	$data=array();
$data['cart']  = $this->cart->contents();


$this->load->view('front/checkout2',$data);
}


function checkout3(){
// Load "billing_view".
	$data=array();
$data['cart']  = $this->cart->contents();


$this->load->view('front/checkout3',$data);
}


function checkout4(){
// Load "billing_view".
	$data=array();

$data['cart']  = $this->cart->contents();

$this->load->view('front/checkout4',$data);
}

public function save_order()
{
// This will store all values which inserted from user.
$customer = array(
'name' => $this->input->post('name'),
'email' => $this->input->post('email'),
'address' => $this->input->post('address'),
'phone' => $this->input->post('phone')
);
// And store user information in database.
$cust_id = $this->billing_model->insert_customer($customer);

$order = array(
'date' => date('Y-m-d'),
'customerid' => $cust_id
);

$ord_id = $this->billing_model->insert_order($order);

if ($cart = $this->cart->contents()):
foreach ($cart as $item):
$order_detail = array(
'orderid' => $ord_id,
'productid' => $item['id'],
'quantity' => $item['qty'],
'price' => $item['price']
);

// Insert product imformation with order detail, store in cart also store in database.

$cust_id = $this->billing_model->insert_order_detail($order_detail);
endforeach;
endif;

// After storing all imformation in database load "billing_success".
$this->load->view('billing_success');
}
}
?>