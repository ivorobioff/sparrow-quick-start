<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Processor extends AbstractProcessor
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
     * @var array
     */
    private $data;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $container->get(ServerRequestInterface::class);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if ($this->data === null){
            $this->data = json_decode((string) $this->request->getBody(), true);

            if (!$this->data){
                $this->data = [];
            }
        }

        return $this->data;
    }
}