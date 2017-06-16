<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Api\Searchable\AbstractSearchableProcessor;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class SearchableProcessor extends AbstractSearchableProcessor
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ContainerInterface
     */
    protected $container;

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
            $this->data = $this->request->getQueryParams();
        }

        return $this->data;
    }
}