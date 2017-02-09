<?php

use Illuminate\Database\Seeder;
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

        for ($i=1; $i<=80; $i++) {
        	$user = rand(1, 2);
        	$status = rand(1, 3);
        	$articles[] = [
        	    "title" => "article #" . $i,
        	    "short_description" => "article short description " . $i,
        	    "content" => "article content " . $i,
        	    "link" => "article".$i,
        	    "user_id" => $user,
        	    "status" => $status,
        	    "thumbnail" => "http://placehold.it/300/09f/fff.png"
        	];
        }

        Article::insert($articles);
    }
}
