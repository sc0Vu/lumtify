<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Category;
use App\Repositories\CategoryRepository;

class CategoryRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test get categories.
     *
     * @return void
     */
    public function testGetCategories()
    {
        $category = new Category;
        $repository = new CategoryRepository();
        $result = $repository->categories(10, ["*"], "page", 1);
        $this->assertEquals(10, count($result));
    }

    /**
     * Test create category.
     * 
     * @return void
     */
    public function testCreate()
    {
        $repository = new CategoryRepository();
        $this->assertTrue($repository->create([
            "name" => "yotesttttt",
            "slug" => "yotest",
            "parent_id" => 0,
            "child_id" => 0,
        ]));
    }

    /**
     * Test get category.
     *
     * @return void
     */
    public function testGetCategory()
    {
        $category = new Category;
        $repository = new CategoryRepository();
        $this->assertEquals($repository->read("water"), $category->where("slug", "water")->first());
    }

    /**
     * Test update category.
     * 
     * @return void
     */
    public function testUpdate()
    {
        $category = Category::first();
        $repository = new CategoryRepository();
        $this->assertTrue($repository->update($category, [
            "name" => "lumtify",
        ]));
    }

    /**
     * Test delete article.
     * 
     * @return void
     */
    public function testDelete()
    {
        $category = Category::first();
        $repository = new CategoryRepository();
        $this->assertTrue($repository->delete($category));
    }
}
