<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {
	public function __construct()
{
    parent::__construct();

    $this->load->model('M_Crud','mcrud');        
}

	public function index()
	{
      $this->load->view('front/home');
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
        echo "Register";
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


    public function detail(){
       
        $this->load->view('front/detail');
    }

}
