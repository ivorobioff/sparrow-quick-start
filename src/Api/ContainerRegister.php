<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ActionMiddlewareRegisterInterface;
use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\ContainerRegisterInterface;
use ImmediateSolutions\Support\Framework\MiddlewareRegisterInterface;
use ImmediateSolutions\Support\Permissions\DefaultPermissionsFactory;
use ImmediateSolutions\Support\Permissions\PermissionsInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ContainerRegister implements ContainerRegisterInterface
{
    public function register(ContainerPopulatorInterface $populator)
    {
        $populator
            ->instance(MiddlewareRegisterInterface::class, MiddlewareRegister::class)
            ->instance(ActionMiddlewareRegisterInterface::class, ActionMiddlewareRegister::class)
            ->instance(ResponseFactoryInterface::class, JsonResponseFactory::class)
            ->instance(PermissionsInterface::class, new DefaultPermissionsFactory())
            ->initialize(AbstractProcessor::class, function(AbstractProcessor $processor){
                $processor->validate();
            });
    }
}