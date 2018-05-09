<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {

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

    public function detail(){
       
        $this->load->view('front/detail');
    }

}
