<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;
use App\Models\Result;
class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Score::orderByDesc('score')->take(20)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'score' => 'required|integer',
            'average_time' => 'required|numeric',
            'answers' => 'required|array',
        ]);

        $result = Result::create($data);
        return response()->json(['message' => 'Quiz submitted successfully!', 'result' => $result], 201);
    }

    public function leaderboard()
    {
        return Result::orderByDesc('score')->take(10)->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
