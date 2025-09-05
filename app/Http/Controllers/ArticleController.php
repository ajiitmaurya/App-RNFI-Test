<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RNFIApiService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(RNFIApiService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index()
    {
        $articles = $this->articleService->getAll() ?? [];
        return view('articles.index', compact('articles'));
    }

    public function show($id)
    {
        $article = $this->articleService->get($id);
        return json_encode(['code' => 'success', 'data' => $article]);
    }

    public function store(Request $request)
    {
        if (!empty($request->title) && !empty($request->content)) {
            $article = $this->articleService->create($request->title, $request->content);
            if (!empty($article)) {
                return json_encode(['code' => 'success', 'msg' => 'New article created!']);
            }
            return json_encode(['code' => 'failed', 'msg' => 'Article not created!']);
        }

        return json_encode(['code' => 'failed', 'msg' => 'insufficient params!']);
    }

    public function update(Request $request, $id)
    {
        if (!empty($request->title) && !empty($request->content)) {
            $article = $this->articleService->update($id, $request->title, $request->content);
            if (!empty($article)) {
                return json_encode(['code' => 'success', 'msg' => 'Article updated!']);
            }
            return json_encode(['code' => 'success', 'msg' => 'Article not updated!']);
        }
        return json_encode(['code' => 'failed', 'msg' => 'insufficient params!']);
    }

    public function destroy($id)
    {
        $article = $this->articleService->delete($id);
        if ($article) {
            return json_encode(['code' => 'success', 'msg' => 'Article deleted!']);
        }
        return json_encode(['code' => 'failed', 'msg' => 'Article not deleted!']);
    }
}
