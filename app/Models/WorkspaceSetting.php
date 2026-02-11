<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkspaceSetting extends Model
{
    public function preset()
    {
        return [
            'ai_enabled' => ['no', 'yes'],
            'auto_reply_per_post_quota' => [1, 10],
            'reply_by_sentiment' => ['All', 'Only Positive', 'Only Negative'],
            'react_to_comment' => ['no', 'yes'],
        ];
    }
}
