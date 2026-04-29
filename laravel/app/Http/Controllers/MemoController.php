<?php

namespace App\Http\Controllers;
use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'nullable',
            'due' => 'nullable',
        ]);

        $memo = Memo::create($validated);
        return response()->json($memo, 201);
    }
    public function index()
    {
        $memos = Memo::orderBy('due', 'asc')->get();
        return response()->json($memos);
    }
    public function destroy(Memo $memo)
    {
        $memo->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    public function update(Request $request, Memo $memo)
    {
        $memo->update($request->only(['title', 'content', 'due']));
        return response()->json($memo);
    }
}
