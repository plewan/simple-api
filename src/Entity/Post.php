<?php

namespace App\Entity;

class Post
{
    public function __construct(
        private int $userId, 
        private int $id, 
        private string $title, 
        private string $body
    ) {}

    public static function fromObject($data): Post
    {
        return new Post($data->userId, $data->id, $data->title, $data->body);
    }

    public function getData()
    {
        return get_object_vars($this);
    }
}