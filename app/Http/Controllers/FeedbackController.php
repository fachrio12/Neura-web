<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'suggestion' => 'required|string|max:5000',
            'session_id' => 'required|exists:user_assessment_sessions,id',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'session_id' => $request->session_id,
            'feedback_text' => $request->suggestion,
        ]);

        return back()->with('success', 'Saran berhasil disimpan!');
    }
}
