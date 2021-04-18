<?php

namespace App\EventListener;

use App\Exception\ClientException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;    
    }

    public function __invoke(ExceptionEvent $event)
    {
        $e = $event->getThrowable();
        $response = new JsonResponse();
        $data = [
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ];
        if ($e instanceof ClientException) {
            $response->setStatusCode($e->getCode());
            $response->setData($data);
        } else {
            $this->logger->error('Internal server error', $data);
            $response->setStatusCode(500);
            $response->setData([
                'code' => 500,
                'message' => 'Internal server error'
            ]);
        }

        $event->setResponse($response);
    }
}