<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\JWTAuth;
use Log;
use Gate;
use Validator;
use App\Article;
use App\Repositories\ArticleRepository;

class ArticleController extends Controller
{
    /**
     * The article repository instance.
     * 
     * @var \App\Repository\ArticleRepository
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
     * @param \App\Repositories\ArticleRepository $repository
     * @param \Tymon\JWTAuth\JWTAuth  $auth
     * @return void
     */
    public function __construct(ArticleRepository $repository, JWTAuth $auth)
    {
        $this->repository = $repository;
        $this->auth = $auth;
    }

    /**
     * Articles.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function articles(Request $request)
    {
        $user = $this->auth->user();
        $query = $request->query();
        $page = isset($query["page"]) ? (preg_match("/[\d]+/", $query["page"]) ? $query["page"] : 1) : 1;
        $per = isset($query["per"]) ? (preg_match("/[\d]+/", $query["per"]) ? $query["per"] : 10) : 10;
        $category = isset($query["category"]) ? $query["category"] : "";

        if (!$user) {
            $articles = $this->repository->articles($per, ["id", "title", "short_description", "thumbnail", "link", "user_id", "created_at", "updated_at"], "page", $page, [Article::STATUS_PUBLISHED], 0, $category);
        } else if ($user->isAdmin()) {
            $articles = $this->repository->articles($per, ["id", "title", "short_description", "thumbnail", "link", "user_id", "created_at", "updated_at"], "page", $page, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE], 0, $category);
        } else if($user->isEditor()) {
            $articles = $this->repository->articles($per, ["id", "title", "short_description", "thumbnail", "link", "user_id", "created_at", "updated_at"], "page", $page, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE], $user->id, $category);
        } else {
            $articles = $this->repository->articles($per, ["id", "title", "short_description", "thumbnail", "link", "user_id", "created_at", "updated_at"], "page", $page, [Article::STATUS_PUBLISHED], 0, $category);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "success" => true,
            "articles" => $articles
        ]);
    }
    
    /**
     * Create article.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $article = new Article;

        if (Gate::denies("create", $article)) {
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

        $data["user_id"] = $this->auth->user()->id;

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
     * Read article.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $link
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, $link)
    {
        if (!$this->validateLink($link)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }

        $user = $this->auth->user();

        if (!$user) {
            $article = $this->repository->read($link);
        } else if ($user->isAdmin()) {
            $article = $this->repository->read($link, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE]);
        } else if($user->isEditor()) {
            $article = $this->repository->read($link, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE], $user->id);
        } else {
            $article = $this->repository->read($link);
        }
        if (empty($article)) {
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
            "article" => $article,
            "success" => true
        ]);
    }

    /**
     * Update article.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $link
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $link)
    {
        if (!$this->validateLink($link)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }
        
        $article = $this->repository->read($link, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE]);

        if (empty($article)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.404"),
                "success" => false
            ], 404);
        }
        if (Gate::denies("update", $article)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        $data = $request->all();
        $validator = $this->validateUpdate($data, $article->id);

        if ($validator->fails()) {
            return response()->json([
                "errs" => [],
                "errFor" => $validator->errors(),
                "msg" => trans("info.failed.validate"),
                "success" => false
            ], 400);
        }
        if ($this->repository->update($article, $data)) {
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
     * Delete article.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $link
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $link)
    {
        if (!$this->validateLink($link)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }
        
        $article = $this->repository->read($link, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE]);

        if (empty($article)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.404"),
                "success" => false
            ], 404);
        }
        if (Gate::denies("delete", $article)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        if ($this->repository->delete($article)) {
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
            "title" => "required|string|max:255",
            "link" => "required|alpha_dash|unique:articles,link",
            "short_description" => "required|string|max:255|unique:users,email",
            "content" => "required",
            "thumbnail" => "required|max:255|active_url",
            "status" => "required|in:1,2,3",
            "categories.*" => "exists:categories,slug"
        ]);
    }

    /**
     * Validate update request data.
     * 
     * @param array $data
     * @param integer $articleId
     * @return Validator
     */
    protected function validateUpdate($data, $articleId)
    {
        return Validator::make($data, [
            "title" => "string|max:255",
            "link" => [
                "alpha_dash",
                Rule::unique('articles')->ignore($articleId)
            ],
            "short_description" => "string|max:255|unique:users,email",
            "content" => "",
            "thumbnail" => "max:255|active_url",
            "status" => "in:1,2,3",
            "categories.*" => "exists:categories,slug"
        ]);
    }

    /**
     * Validate link.
     * 
     * @param string $link
     * @return boolean
     */
    public function validateLink($link)
    {
        if (!preg_match("/[+&=%'\"\\\s]+/", $link)) {
            return true;
        }
        return false;
    }
}
