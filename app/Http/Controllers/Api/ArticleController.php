<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * create article api.
     * 
     * @param  Request $request
     * @return json
     */
    public function create(Request $request)
    {
        $repository = $this->repository;

        if (Gate::denies("create", $repository)) {
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
        if ($repository->create($data)) {
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
     * validate create request data
     * 
     * @param  array $data
     * @return Validator
     */
    protected function validateCreate($data)
    {
        return Validator::make($data, [
            "name" => "required|string|max:255",
            "email" => "required|email|string|max:255|unique:users,email",
            "pass" => "required",
            "pass_verify" => "required|same:pass"
        ]);
    }
}
