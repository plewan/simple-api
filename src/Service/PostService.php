<?php

namespace App\Service;

class PostService
{
    private $handler;

    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function getPost(int $postId)
    {
        //getData - transfor Post object to array, ready for json serialization
        return $this->handler->getPost($postId)->getData();
    }

    public function getAllPost()
    {
        //same aas above - transform Posts into simple arrays
        return array_map(fn ($post) => $post->getData(), $this->handler->getAllPosts());
    }
}