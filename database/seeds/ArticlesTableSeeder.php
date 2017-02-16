<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Article;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = [];
        $now = new Carbon;

        for ($i=1; $i<=80; $i++) {
        	$user = rand(1, 3);
        	$status = rand(1, 3);
        	$articles[] = [
                "id" => $i,
        	    "title" => "article #" . $i,
        	    "short_description" => "article short description " . $i,
        	    "content" => "article content " . $i,
        	    "link" => "article".$i,
        	    "user_id" => $user,
        	    "status" => $status,
        	    "thumbnail" => "http://placehold.it/300/09f/fff.png",
                "created_at" => $now,
                "updated_at" => $now
        	];
        }

        Article::insert($articles);
    }
}
