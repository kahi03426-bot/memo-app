<?php

namespace App\Http\Controllers;
use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable',
            'content' => 'required',
        ]);

        $memo = Memo::create($validated);
        return response()->json($memo, 201);
    }
    public function index()
    {
        // データベースからすべてのメモを、新着順（作成日の降順）で取得
        $memos = Memo::orderBy('created_at', 'desc')->get();

        // JSON形式でVueに返す
        return response()->json($memos);
    }
    public function destroy(Memo $memo)
    {
        $memo->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    public function update(Request $request, Memo $memo)
    {
        $memo->update($request->only(['title', 'content']));
        return response()->json($memo);
    }
}
