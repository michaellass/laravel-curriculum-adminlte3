<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogbookEntry extends Model
{
    protected $guarded = [];
    
    public function path()
    {
        return "/logbookEntries/{$this->id}";
    }
    
    public function logbook(){
        return $this->belongsTo('App\Logbook')->withTimestamps();
    }
    
    public function contentSubscriptions()
    {
        return $this->morphMany('App\ContentSubscription', 'subscribable');
    }
    
    public function enablingObjectiveSubscriptions()
    {
        return $this->morphMany('App\EnablingObjectiveSubscriptions', 'subscribable');
    }
    
    public function terminalObjectiveSubscriptions()
    {
        return $this->morphMany('App\TerminalObjectiveSubscriptions', 'subscribable');
    }
    
    public function taskSubscription()
    {
        return $this->morphMany('App\TaskSubscription', 'subscribable');
    }
    
    public function mediaSubscriptions()
    {
        return $this->morphMany('App\MediumSubscription', 'subscribable');
    }
    
    public function media()
    {
        return $this->hasManyThrough(
            'App\Medium',
            'App\MediumSubscription',
            'subscribable_id', // Foreign key on medium_subscription table...
            'id', // Foreign key on medium table...
            'id', // Local key on enabling_objectives table...
            'medium_id' // Local key on medium_subscription table...
        )->where('subscribable_type', get_class($this)); 
    }
}
