<?php
namespace ImmediateSolutions\Support\Permissions;

use ImmediateSolutions\Support\Framework\ContainerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class DefaultPermissionsFactory
{
    /**
     * @param ContainerInterface $container
     * @return Permissions
     */
    public function __invoke(ContainerInterface $container)
    {
        return new Permissions(function($class) use ($container){
            return $container->get($class);
        });
    }
}