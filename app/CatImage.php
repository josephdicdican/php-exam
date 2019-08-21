<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatImage extends Model
{
    protected $table = 'cat_images';

    protected $guarded = ['id'];

    public $timestamps = true;

    protected $fillable = [
    	'category_id',
    	'source_url',
    	'url',
    	'created_at',
    	'updated_at',
    ];

    public function scopeCategoryId($query, $category_id)
    {
    	if($category_id) { 
    		return $query->where('category_id', $category_id);
    	}
    }
}
