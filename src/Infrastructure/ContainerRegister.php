<?php
namespace ImmediateSolutions\Support\Infrastructure;

use ImmediateSolutions\Support\Core\Interfaces\ContainerInterface;
use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\ContainerRegisterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class ContainerRegister implements ContainerRegisterInterface
{
    /**
     * @param ContainerPopulatorInterface $populator
     */
    public function register(ContainerPopulatorInterface $populator)
    {
        $populator->service(ContainerInterface::class, function(\ImmediateSolutions\Support\Framework\ContainerInterface $container){
            return new Container($container);
        });
    }
}