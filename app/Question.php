<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Console\RetryCommand;
use Illuminate\Support\Str;

class Question extends Model
{
    protected $guarded = [];


    /**
     * MUTATORS :- These are special functions ehich have setXXXAttributeValue($value)
     */

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }


    /**
     * ACCESSORS :- These are special functions which have getXXXAttribute()
     */

    public function getUrlAttribute()
    {
    return "/questions/{$this->slug}";
    }

    public function getCreatedDateAttribute()
    {
    return $this->created_at->diffForHumans();
    }

    public function getAnswersStyleAttribute()
    {
    if ($this->answers_count > 0) {
        if ($this->best_answer_id) {
            return 'has-best-answer';
        }
        return 'answered';
    }
    return 'unanswered';
    }

    public function getFavoriteCountAttribute()
    {
    return $this->favorites->count();
    }

    public function getIsFavoriteAttribute()
    {
        return $this->favorites()->where(['user_id'=>auth()->id()])->count() > 0;
    }



    /**
     * RELATIONSHIP METHODS
     */

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
    public function favorites()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function votes()
    {
        
        return $this->morphToMany(User::class, 'vote')->withTimestamps();
    }
    
    public function vote(int $vote)
    {
        // dd($this->votes());
        $this->votes()->attach(auth()->id(), ['vote' => $vote]);
        // dd('here i am');
        if($vote < 0) {
            $this->decrement('votes_count');
        }else {
            $this->increment('votes_count');
        }
    }

    public function updateVote(int $vote)
    {
        $this->votes()->updateExistingPivot(auth()->id(), ['vote'=>$vote]);
        if($vote < 0) {
            $this->decrement('votes_count');
            $this->decrement('votes_count');
        }else {
            $this->increment('votes_count');
            $this->increment('votes_count');
        }
    }

    /**
     * HELPER FUNCTIONS
     */

    public function markAsBest(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }
}
