<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::observe(PostObserver::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('workspace', function ($builder) {
            if (auth()->check()) {
                // $builder->where('workspace_id', auth()->user()->default_workspace_id);
            }
        });
    }

    public function page()
    {
        return $this->hasOne(\App\Models\Page::class, 'remote_id', 'page_id');
    }
}
