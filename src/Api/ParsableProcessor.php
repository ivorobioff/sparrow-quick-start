<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class ParsableProcessor extends AbstractParsableProcessor
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
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $container->get(ServerRequestInterface::class);
    }

    /**
     * @return string|array
     */
    protected function getContent()
    {
        if (in_array($this->getContentType(), ['application/x-www-form-urlencoded', 'multipart/form-data'])){
            return $this->request->getParsedBody();
        }

        return (string) $this->request->getBody();
    }

    /**
     * @return string
     */
    protected function getContentType()
    {
        return $this->request->getHeaderLine('Content-Type');
    }
}