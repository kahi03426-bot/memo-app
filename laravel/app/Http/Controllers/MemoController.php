<?php

namespace App\Http\Controllers;
use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    public function store(Request $request)
    {
        // 【デバッグ用】実際に届いている生データをログに書き出す
        \Log::info('Vueから届いた生データ:', $request->all());

        // バリデーションを一時的にコメントアウト（または緩くする）
        $validated = $request->validate([
            'title' => 'nullable',
            'content' => 'required',
        ]);

        $memo = Memo::create($validated);
        return response()->json($memo, 201);
    }
}
