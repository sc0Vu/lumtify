<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$words = [
    	    "coat","smile","table","egg","bat","pants","mouth","dog","candle","pen","football","doll","ear","octopus","bug","bee","swing","beach","balloon","baby","mountain","ocean","lollipop","snowman","desk","boat","door","ant","square","monkey","pencil","alligator","stairs","corn","feet","bike","cookie","inchworm","chicken","finger","bone","spoon","drum","nose","seashell","airplane","wheel","face","turtle","mouse","shirt","bread","basketball","bed","apple","water","oval","kite","slide","jellyfish","boy","truck","lizard","light","grapes","cupcake","legs","hand","snowflake","cow","bird","lion","ice cream cone","blanket","bell","crab","ears","horse","circle","monster","star","carrot","sunglasses","motorcycle","helicopter","leaf","car","lemon","tree","bear","banana","flower","butterfly","heart","bus","frog","pizza","snake","clock","computer","pie","rocket","socks","chair","Mickey Mouse","robot","bowl","book","spider web","giraffe","pig","ball","lamp","dinosaur","spider","glasses","eyes","tail","hamburger","train","dragon","girl","shoe","bridge","elephant","cheese","cloud","moon","sun","grass","hippo","head","bunny","hat","eye","jar","bracelet","bunk bed","worm","milk","caterpillar","person","cat","whale","broom","blocks","jacket","ring","bench","ghost","cherry","orange","snail","skateboard","lips","cup","purse","branch","duck","house"
    	];
    	$categories = [];

    	foreach ($words as $key => &$word) {
    		$categories[] = [
    		    "id" => ($key + 1),
    		    "parent_id" => 0,
    		    "child_id" => 0,
    		    "slug" => $word,
    		    "name" => $word
    		];
    	}
        Category::insert($categories);
    }
}
