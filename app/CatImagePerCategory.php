<?php

namespace App;

class CatImagePerCategory
{
    public $results_per_page = 0;
    public $category = null;
    public $category_id = 0;

    public function __construct($data) 
    {
    	$this->results_per_page = $data['results_per_page'];
    	$this->category = $data['category'];
    	$this->category_id = $data['category_id'];
    }
}
