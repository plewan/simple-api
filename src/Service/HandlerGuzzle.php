<?php

namespace App\Service;

use App\Entity\Post;
use App\Exception\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HandlerGuzzle implements HandlerInterface
{
    private $client;

    public function __construct($base_uri)
    {
        $stack = HandlerStack::create();
        /**
         * Exception middleware - throw ClientException instead of Guzzle-specific exception
         */
        $stack->push(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $promise = $handler($request, $options);
                return $promise->then(
                    function (ResponseInterface $response) {
                        $statusCode = $response->getStatusCode();
                        if ($statusCode < 400) {
                            return $response;
                        } else if ($statusCode < 500) {
                            throw new ClientException($statusCode, $response->getReasonPhrase());
                        } else {
                            throw new \Exception($statusCode, $response->getReasonPhrase());
                        }
                    }
                );
            };
        });
        $this->client = new Client(['base_uri' => $base_uri, 'handler' => $stack]);
    }

    /**
     * @param int $postId
     *
     * @return Post
     */
    public function getPost(int $postId): Post
    {
        $response = $this->client->get("posts/{$postId}");
        $data = json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR); //ugly, fix in php 8
        return Post::fromObject($data);
    }
    
    /**
     * @return Post[]
     */
    public function getAllPosts(): array
    {
        $response = $this->client->get('posts');
        $posts = json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR); //ugly, fix in php 8
        return array_map(fn ($data) => Post::fromObject($data), $posts);
    } 
}
