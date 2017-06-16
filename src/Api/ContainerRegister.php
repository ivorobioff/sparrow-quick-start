<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\ContainerRegisterInterface;
use ImmediateSolutions\Support\Framework\MiddlewareRegisterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ContainerRegister implements ContainerRegisterInterface
{
    public function register(ContainerPopulatorInterface $populator)
    {
        $populator
            ->instance(MiddlewareRegisterInterface::class, MiddlewareRegister::class)
            ->instance(ResponseFactoryInterface::class, JsonResponseFactory::class)
            ->initialize(AbstractProcessor::class, function(AbstractProcessor $processor){
                $processor->validate();
            });
    }
}