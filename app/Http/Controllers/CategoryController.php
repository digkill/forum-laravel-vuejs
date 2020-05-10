<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryTopicsResource;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function __construct()
    {
       /* $this->middleware('JWT', [
            'except' => [
                'index',
                'show',
                'getQuestionsByCategorySlug'
            ]
        ]);*/
      /*  $this->middleware('auth.role:admin', [
            'only' => ['store', 'update', 'destroy']
        ]);*/
    }

    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function store(CategoryRequest $request) {
        $request['slug'] = Str::slug($request->name, '-');
        Category::create($request->all());
        return response('Created', Response::HTTP_CREATED);
    }

    public function show(Category $category)
    {
        return new CategoryTopicsResource($category);
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->update (
            [
                'name' => $request->name,
                'slug' => str_slug($request->name)
            ]
        );
        return response(new CategoryResource($category), Response::HTTP_ACCEPTED);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category) {
        $category->delete();
        return response(null. Response::HTTP_NO_CONTENT);
    }

    public function getQuestionsByCategorySlug(Request $request, Category $category) {

        $id = $category->id;
        $page = $request->get( 'per-page', 10 );
        return  CategoryQuestionsResource::collection(Question::whereCategory_id($id)->paginate ( $page ) );
    }
}
