<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('workspace', function ($builder) {
            if (auth()->check()) {
                $builder->where('workspace_id', auth()->user()->default_workspace_id);
            }
        });
    }

    public function pages()
    {
        return $this->hasMany(\App\Models\Page::class);
    }
}
