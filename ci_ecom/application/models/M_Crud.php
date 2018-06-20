<?php

 class M_Crud extends CI_Model
 {

 	function get_by_sql($sql, $option=false){
		$query	= $this->db->query($sql);
		
		if($option == 'trace')
			print_r($this->db->queries);		
			
		if(!empty($query))
		{
			$results = array();
			if ( $query->num_rows() > 0 )
				$results = $query->result_array();
			return $results;	
		}
	}

  function get_by_field($sql, $option=false){
    $query  = $this->db->query($sql);
    
    if($option == 'trace')
      print_r($this->db->queries);    
      
    if(!empty($query))
    {
      $results = array();
      if ( $query->num_rows() > 0 )
        $results = $query->result_array();
      return $results;  
    }
  }

 	
    function category()
    {
        $query=$this->db->query("SELECT cat_title FROM categories");
        return $query->result();
     }

     function getProducts($catId)
 {
   	$this -> db -> select('*');
   	$this -> db -> from('products');
		$this -> db -> where('catId = $catId');
		
		echo "Here $catId<br />";
   	$query = $this -> db -> get();
   	if($query -> num_rows() >= 1)
   	{
     	return $query->result();
   	}
   	else
   	{
   	  return false;
   	}
 	}

function brand_name($id){
    $this->db->select('brand_title');
    $this->db->from('brands');
    $this->db->where('brand_id',$id);
    return $this->db->get('brands');
}


}