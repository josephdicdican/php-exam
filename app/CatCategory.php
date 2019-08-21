<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatCategory extends Model
{
    protected $table = 'cat_categories';

    protected $guarded = ['id'];

    public $timestamps = true;

    protected $fillable = [
    	'name',
    	'created_at',
    	'updated_at',
    ];
}
