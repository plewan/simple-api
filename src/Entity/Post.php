<?php

namespace App\Entity;

class Post
{
    private $userId;
    private $id;
    private $title;
    private $body;

    public function __construct(int $userId, int $id, string $title, string $body)
    {
        //php 8...
        $this->userId = $userId;
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
    }

    public static function fromObject($data): Post
    {
        return new Post($data->userId, $data->id, $data->title, $data->body);
    }

    public function getData()
    {
        return get_object_vars($this);
    }
}