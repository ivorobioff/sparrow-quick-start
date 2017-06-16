<?php
namespace ImmediateSolutions\Support\Api;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use ImmediateSolutions\Support\Pagination\AdapterInterface;
use ImmediateSolutions\Support\Pagination\Describer;
use ImmediateSolutions\Support\Pagination\PaginationProviderInterface;
use ImmediateSolutions\Support\Pagination\Paginator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class Controller
{
    /**
     * @var Reply
     */
    protected $reply;

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
        $this->reply = $container->get(Reply::class);
        $this->request = $container->get(ServerRequestInterface::class);

        if (method_exists($this, 'initialize')){
            $this->container->call([$this, 'initialize']);
        }
    }

    /**
     * @param AdapterInterface $adapter
     * @return object[]|PaginationProviderInterface
     */
    public function paginator(AdapterInterface $adapter)
    {
        return new Paginator($adapter, new Describer($this->request));
    }
}