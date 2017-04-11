<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\JWTAuth;
use Log;
use Gate;
use Validator;
use App\Category;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * The category repository instance.
     * 
     * @var \App\Repository\CategoryRepository
     */
    protected $repository;

    /**
     * The authentication guard factory instance.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\CategoryRepository $repository
     * @param \Tymon\JWTAuth\JWTAuth  $auth
     * @return void
     */
    public function __construct(CategoryRepository $repository, JWTAuth $auth)
    {
        $this->repository = $repository;
        $this->auth = $auth;
    }

    /**
     * Categories.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $query = $request->query();
        $page = isset($query["page"]) ? (preg_match("/[\d]+/", $query["page"]) ? $query["page"] : 1) : 1;
        $per = isset($query["per"]) ? (preg_match("/[\d]+/", $query["per"]) ? $query["per"] : 10) : 10;
        $categories = $this->repository->categories($per, ["name", "slug", "parent_id", "child_id"], "page", $page);
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "success" => true,
            "categories" => $categories
        ]);
    }
    
    /**
     * Create category.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $category = new Category;

        if (Gate::denies("create", $category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        $data = $request->all();
        $validator = $this->validateCreate($data);

        if ($validator->fails()) {
            return response()->json([
                "errs" => [],
                "errFor" => $validator->errors(),
                "msg" => trans("info.failed.validate"),
                "success" => false
            ], 400);
        }
        if ($this->repository->create($data)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("info.success.create"),
                "success" => true
            ]);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => trans("info.failed.create"),
            "success" => false
        ], 500);
    }

    /**
     * Read category.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, $slug)
    {
        if (!$this->validateSlug($slug)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }
        $category = $this->repository->read($slug);

        if (empty($category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.404"),
                "success" => false
            ], 404);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "category" => $category,
            "success" => true
        ]);
    }

    /**
     * Update category.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $slug)
    {
        if (!$this->validateSlug($slug)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }
        $category = $this->repository->read($slug);

        if (empty($category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.404"),
                "success" => false
            ], 404);
        }
        if (Gate::denies("update", $category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        $data = $request->all();
        $validator = $this->validateUpdate($data, $category->id);

        if ($validator->fails()) {
            return response()->json([
                "errs" => [],
                "errFor" => $validator->errors(),
                "msg" => trans("info.failed.validate"),
                "success" => false
            ], 400);
        }
        if ($this->repository->update($category, $data)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("info.success.update"),
                "success" => true
            ]);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => trans("info.failed.update"),
            "success" => false
        ], 500);
    }
    
    /**
     * Delete category.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $slug)
    {
        if (!$this->validateSlug($slug)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }
        $category = $this->repository->read($slug);

        if (empty($category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.404"),
                "success" => false
            ], 404);
        }
        if (Gate::denies("delete", $category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        if ($this->repository->delete($category)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("info.success.delete"),
                "success" => true
            ]);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => trans("info.failed.delete"),
            "success" => false
        ], 500);
    }

    /**
     * Validate create request data.
     * 
     * @param array $data
     * @return Validator
     */
    protected function validateCreate($data)
    {
        return Validator::make($data, [
            "name" => "required|string|max:255",
            "slug" => "required|alpha_dash|unique:categories,slug|string|max:50",
            "parent_id" => "numeric|exists:categories,id",
            "child_id" => "numeric|exists:categories,id",
        ]);
    }

    /**
     * Validate update request data.
     * 
     * @param array $data
     * @param integer $categoryId
     * @return Validator
     */
    protected function validateUpdate($data, $categoryId)
    {
        return Validator::make($data, [
            "name" => "string|max:255",
            "slug" => [
                "alpha_dash",
                "string",
                "max:50",
                Rule::unique('categories')->ignore($categoryId)
            ],
            "parent_id" => "numeric|exists:categories,id",
            "child_id" => "numeric|exists:categories,id",
        ]);
    }

    /**
     * Validate slug.
     * 
     * @param string $slug
     * @return boolean
     */
    public function validateSlug($slug)
    {
        if (!preg_match("/[+&=%'\"\\\s]+/", $slug)) {
            return true;
        }
        return false;
    }
}
