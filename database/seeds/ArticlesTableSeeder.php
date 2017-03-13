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
        	$user = rand(1, 2);
        	$status = rand(1, 3);
        	$articles[] = [
                "id" => $i,
        	    "title" => "article #" . $i,
        	    "short_description" => "article short description " . $i,
        	    "content" => "article content " . $i,
        	    "link" => "article".$i,
        	    "user_id" => $user,
        	    "status" => $status,
        	    "thumbnail" => "https://placeholdit.imgix.net/~text?txtsize=28&bg=0099ff&txtclr=ffffff&txt=hello+lumtify&w=300&h=300&fm=png",
                "created_at" => $now,
                "updated_at" => $now
        	];
        }

        Article::insert($articles);
    }
}
