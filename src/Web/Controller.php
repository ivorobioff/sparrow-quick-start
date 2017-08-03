<?php
namespace ImmediateSolutions\Support\Web;

use ImmediateSolutions\Support\Api\Reply;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var Reply
     */
    protected $reply;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $container->get(ServerRequestInterface::class);
        $this->renderer = $container->get(RendererInterface::class);
        $this->reply = $container->get(Reply::class);

        if (method_exists($this, 'initialize')){
            $this->container->call([$this, 'initialize']);
        }
    }

    /**
     * @param $path
     * @param array $parameters
     * @return ResponseInterface
     */
    protected function show($path, array $parameters = [])
    {
        $response = new Response('php://memory', 200, ['Content-Type' => 'text/html']);

        $response->getBody()->write($this->renderer->render($path, $parameters));

        return $response;
    }

    /**
     * @param string $url
     * @return ResponseInterface
     */
    protected function redirect($url)
    {
        return new Response('php://memory', 200, ['location' => $url]);
    }
}