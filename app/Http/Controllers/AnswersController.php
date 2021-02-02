<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests\CreateAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Notifications\NewReplyAdded;
use App\Question;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    
    public function store(CreateAnswerRequest $request, Question $question)
    {
        $question->answers()->create([
            'body'=>$request->body,
            'user_id'=>auth()->id()
        ]);

        $question->owner->notify(new NewReplyAdded($question));

        session()->flash('success', 'Your answer submitted succesfully!');
        return redirect($question->url);
    }

    public function edit(Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);
        return view('answers.edit', compact([
            'question',
            'answer'
        ]));
    }

    public function update(UpdateAnswerRequest $request, Question $question, Answer $answer)
    {
        $answer->update([
            'body' => $request->body
        ]);
        session()->flash('success', 'Your answer updated succesfully!');
        return redirect($question->url);
    }

    public function destroy(Question $question, Answer $answer)
    {
        // dd($answer);
        $this->authorize('delete', $answer);
        
        $answer->delete();
        session()->flash('success', 'Your answer deleted succesfully!');
        return redirect($question->url);
    }

    public function bestAnswer(Request $request, Answer $answer)
    {
        // dd("here i am");
        $this->authorize('markAsBest', $answer);
        $answer->question->markAsBest($answer);
        return redirect()->back();
    }
}