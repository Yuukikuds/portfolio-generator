<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/** The "Feedback" box in the footer. Anyone can send feedback; logged-in users are linked to their account. */
class FeedbackController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Go back to the page the visitor was on (only if it is on this site), at the footer.
        $previous = url()->previous();
        $back = (str_starts_with($previous, url('/')) ? strtok($previous, '#') : route('home')).'#feedback';

        $validator = Validator::make($request->only('feedback_message'), [
            'feedback_message' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'feedback_message.required' => 'Please write your feedback first.',
            'feedback_message.min' => 'Please write at least 5 characters.',
            'feedback_message.max' => 'Please keep your feedback under 1000 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->to($back)
                ->withErrors($validator, 'feedback')
                ->withInput($request->only('feedback_message'));
        }

        Feedback::create([
            'message' => trim($validator->validated()['feedback_message']),
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->to($back)->with('saved', 'Thank you! Your feedback was sent.');
    }
}
