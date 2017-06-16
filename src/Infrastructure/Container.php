<?php
namespace ImmediateSolutions\Support\Infrastructure;

use ImmediateSolutions\Support\Core\Interfaces\ContainerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var \ImmediateSolutions\Support\Framework\ContainerInterface
     */
    private $source;

    /**
     * @param \ImmediateSolutions\Support\Framework\ContainerInterface $source
     */
    public function __construct(\ImmediateSolutions\Support\Framework\ContainerInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @param string $abstract
     * @return object
     */
    public function get($abstract)
    {
        return $this->source->get($abstract);
    }

    /**
     * @param string $abstract
     * @return bool
     */
    public function has($abstract)
    {
        return $this->source->has($abstract);
    }

    /**
     * @param callable $callback
     * @param array $parameters
     * @return mixed
     */
    public function call($callback, array $parameters = [])
    {
        return $this->source->call($callback, $parameters);
    }
}