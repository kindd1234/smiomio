<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Workspace extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($workspace) {
            if (Auth::check()) {
                $workspace->owner_id = Auth::id();
            }
        });
    }
}
