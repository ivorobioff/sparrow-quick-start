<?php
namespace ImmediateSolutions\Support\Api;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class JsonResponseFactory implements ResponseFactoryInterface
{
    /**
     * @param array $content
     * @param int $status
     * @return ResponseInterface
     */
    public function create($content, $status)
    {
        $response = new Response('php://memory', $status, ['Content-Type' => 'application/json']);

        if ($content !== null){
            $response->getBody()->write(json_encode($content));
        }

        return $response;
    }
}