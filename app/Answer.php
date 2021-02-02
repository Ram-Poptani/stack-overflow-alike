<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Answer extends Model
{

    use Notifiable;
    protected $guarded =[];


    /**
     * This is a parent static method to handle events on the Eloquent
     * Overriding it
     */
    public static function boot()
    {
        /**
         * calling the parent boot method
         */
        parent::boot();

        /**
         * event for model created event
         */
        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });

        static::deleted(function ($answer) {
            $answer->question->decrement('answers_count');
        });
    }



    /**
     * ACCESSORS :- These are special functions which have getXXXAttribute()
     */

    public function getCreatedDateAttribute()
    {
       return $this->created_at->diffForHumans();
    }

    public function getBestAnswerStatusAttribute()
    {
        if ($this->id === $this->question->best_answer_id) {
            return "text-success";
        }
        return "text-dark";
    }

    public function getIsBestAttribute()
    {
        return $this->id === $this->question->best_answer_id;
    }


    /*
     * RELATIOSHIP METHODS
     */

    public function question() 
    {
        return $this->belongsTo(Question::class);
    }
    public function author() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function votes()
    {
        return $this->morphToMany(User::class, 'vote')->withTimestamps();
    }

    public function vote(int $vote)
    {
        $this->votes()->attach(auth()->id(), ['vote'=>$vote]);
        if($vote < 0){
            $this->decrement('votes_count');
        }else{
            $this->increment('votes_count');
        }
    }
    public function updateVote(int $vote)
    {
        //User may have already up-voted question and now down votes (vote_count = 10) and now user down votes (vote_count=9) vote_count=8
        $this->votes()->updateExistingPivot(auth()->id(), ['vote'=>$vote]);
        if ($vote < 0) {
            $this->decrement('votes_count');
            $this->decrement('votes_count');
        }else{
            $this->increment('votes_count');
            $this->increment('votes_count');
        }
    }
}

