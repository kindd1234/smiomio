<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
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

    public function parentPage()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function childrens()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }
}
