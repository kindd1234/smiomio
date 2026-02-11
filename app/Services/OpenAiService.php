<?php

namespace App\Services;

class OpenAiService
{
    private $client;

    public function __construct()
    {
        $this->client = Http::post("${api}")
            ->withHeaders();
    }

    public function getReplyComment($post)
    {
        $prompt = '
        
        ';
    }
}
