<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {

	public function __construct()
{
    parent::__construct();

    $this->load->model('M_Crud','mcrud'); 

    //Load Library and model.
$this->load->library('cart');
$this->load->model('billing_model');

}

	public function index()
	{
        $data=array();
        $hotThisWeek="SELECT p.*,b.brand_title,c.cat_title 
FROM products as p INNER JOIN brands as b ON p.product_brand=b.brand_id 
INNER JOIN categories as c ON c.cat_id=p.product_cat LIMIT 7";
        $data['hotThisWeek']=$this->mcrud->get_by_sql($hotThisWeek);

        //Get all data from database
       // $data['products'] = $this->billing_model->get_all();


      $this->load->view('front/home',$data);
    }

public function opencart()
    {
        $data['cart']  = $this->cart->contents();
        $this->load->view("cart_modal", $data);
    }

    
    public function man(){
        echo "Man";
    }

    public function woman(){
        echo "Woman";
    }

    public function login(){
        echo "Login";
    }

    public function register(){
       
       $this->load->view('front/register');
    }
    public function contact(){
        echo "Contact";
    }

    public function category(){

	$data=array();

    	// $result=$this->mcrud->category();
     //    echo json_encode($result);
	$SQL="SELECT * FROM categories";

	$data['cats']=$this->mcrud->get_by_sql($SQL);
    	$this->load->view('front/category-right',$data);
    }

      public function categories(){
    	$result=$this->mcrud->category();
        echo json_encode($result);

    	// $this->load->view('front/category-right',$data);
    }

    function getCategories()
 	{
		$data['cats'] = array();
 		$result = $this->mcrud->getCategories();
	 
		if($result)
		{
     	foreach($result as $row)
			{
				$id = $row->id;
				$name = $row->name;
				
				array_push($data['cats'], array('id'=>$id, 'name'=>$name));
			}
		
			$this->load->view('front/category-right', $data);
		}
 }

    function getProducts($catId)
 {
	 	$data['products'] = array();
	 	$result = $this->mcrud->getProducts($catId);
	 
		if($result)
		{
     	foreach($result as $row)
			{
				$id = $row->product_id;
				$name = $row->product_title;
				
				array_push($data['products'], array('id'=>$id, 'name'=>$name));
			}
		}
		$this->load->view('front/category-right',$data);
 }

 function productsList()
 {
    // $this->load->model('mcrud','', TRUE);
    $data['products'] = $this->mcrud->getProducts($this->params['catId']);
    $this->load->view('front/category-right', $data);
}


public function detail($product_id){
        $data=array();
        $sqlDetail="SELECT * FROM products WHERE product_id=". $product_id;
        $data['getProductDetail']=$this->mcrud->get_by_sql($sqlDetail);

        $sqlOtherProduct="SELECT p.*,b.brand_title,c.cat_title 
FROM products as p INNER JOIN brands as b ON p.product_brand=b.brand_id 
INNER JOIN categories as c ON c.cat_id=p.product_cat WHERE p.product_id<>". $product_id." LIMIT 6";
        $data['otherProducts']=$this->mcrud->get_by_sql($sqlOtherProduct);
        // $brand_id=getProducts[0]['product_brand'];

        // $data['brand']=$this->mcrud->get_by_sql("SELECT brand_title FROM brands WHERE brand_id=$brand_id");




        $this->load->view('front/detail',$data);
    }


    public function cats($cat_di,$brand,$product_id){
        $data=array();
        $sqlDetail="SELECT p.*,b.brand_title,c.cat_title 
FROM products as p INNER JOIN brands as b ON p.product_brand=b.brand_id 
INNER JOIN categories as c ON c.cat_id=p.product_cat WHERE p.product_id=". $product_id;
        $data['getProductDetail']=$this->mcrud->get_by_sql($sqlDetail);

        $sqlOtherProduct="SELECT p.*,b.brand_title,c.cat_title 
FROM products as p INNER JOIN brands as b ON p.product_brand=b.brand_id 
INNER JOIN categories as c ON c.cat_id=p.product_cat WHERE p.product_id<>". $product_id ." LIMIT 7";
        $data['otherProducts']=$this->mcrud->get_by_sql($sqlOtherProduct);


        // Let Category
$catLetf="SELECT p.*,b.brand_title,c.cat_title,count(c.cat_title) as cat_count 
FROM products as p INNER JOIN brands as b ON p.product_brand=b.brand_id 
INNER JOIN categories as c ON c.cat_id=p.product_cat group by c.cat_title";
        $data['catLetf']=$this->mcrud->get_by_sql($catLetf);


 $brandLeft="SELECT p.*,b.brand_title,c.cat_title,count(b.brand_title) as brand_count 
FROM products as p INNER JOIN brands as b ON p.product_brand=b.brand_id 
INNER JOIN categories as c ON c.cat_id=p.product_cat group by b.brand_title";
        $data['brandLeft']=$this->mcrud->get_by_sql($brandLeft);





        $this->load->view('front/detail',$data);
    }




}
