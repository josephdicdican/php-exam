<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\CatCategory;
use App\CatImage;
use App\CatImagePerCategory;
use App\Jobs\FetchCatImagesPerCategory;

class CatImagesController extends Controller
{
	public function index()
	{
		$data = CatImage::categoryId(request()->get('category_id'))->paginate(request()->get('per_page', 10));

		return response()->json($data);
	}

	/**
	 * Dispatches job FetchCatImagePerCategory
	 */
	public function dispatchFetchCatImagesCategory()
	{
		$cat_categories = CatCategory::all();

		foreach($cat_categories as $cat_category) {
			FetchCatImagesPerCategory::dispatch(new CatImagePerCategory([
				'results_per_page' => [10,20][rand(0,1)],
				'category' => $cat_category->name,
				'category_id' => $cat_category->id,
			]));
		}

		return 200;
	}

	public function getCatCategories()
	{
		return response()->json(CatCategory::selectRaw('id, name')->get());
	}
}