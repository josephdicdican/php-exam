<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use App\CatImage;
use App\CatImagePerCategory;

class FetchCatImagesPerCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cat_image_per_category;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CatImagePerCategory $cat_image_per_category)
    {
        $this->cat_image_per_category = $cat_image_per_category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cat_images_data = $this->fetchFromAPI(
            $this->cat_image_per_category->results_per_page,
            $this->cat_image_per_category->category
        );

        if($cat_images_data) {
            $cat_images_data = collect($cat_images_data)->map(function($value) {
                unset($value['id']);
                $value['category_id'] = $this->cat_image_per_category->category_id;
                return $value;
            })->values()->toArray();

            CatImage::insert($cat_images_data);

            $category_cache_count = $this->cat_image_per_category->category.'_count';
            $images_count = count($cat_images_data);

            Log::info(sprintf("%s - %d", $category_cache_count, $images_count));

            if(!Cache::has($category_cache_count)) {
                Cache::set($category_cache_count, $images_count);    
            } 
            
            if(!Cache::has('total_images_fetched')) {
                Cache::set('total_images_fetched', 0);
            }

            Cache::increment($category_cache_count, $images_count);
            Cache::increment('total_images_fetched', $images_count);
        }
    }

    private function fetchFromAPI($results_per_page, $category)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => sprintf("http://thecatapi.com/api/images/get?format=json&results_per_page=%d&category=%s", $results_per_page, $category),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: api.thecatapi.com",
                "cache-control: no-cache"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        } 

        return json_decode($response, true);
    }
}
