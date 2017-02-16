<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $query = $request->query();
        $page = isset($qurey["page"]) ? (preg_match("/^[\d]*$/", $query["page"]) ? $query["page"] : 1): 1;
        $per = isset($qurey["per"]) ? (preg_match("/^[\d]*$/", $query["per"]) ? $query["per"] : 10): 10;;
        $articles = $this->repository->articles($per, ["*"], "page", $page, [Article::STATUS_PUBLISHED]);
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
        
        $article = $this->repository->read($link);

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
     * Validate create request data.
     * 
     * @param array $data
     * @return Validator
     */
    protected function validateCreate($data)
    {
        return Validator::make($data, [
            "title" => "required|string|max:255",
            "link" => "required|alpha_dash",
            "short_description" => "required|string|max:255|unique:users,email",
            "content" => "required",
            "thumbnail" => "required|max:255|active_url",
            "status" => "required|in:1,2,3"
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
