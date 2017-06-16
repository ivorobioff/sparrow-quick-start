<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ActionMiddlewareRegisterInterface;
use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\MiddlewareRegisterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class AbstractContainerRegister extends \ImmediateSolutions\Support\Infrastructure\AbstractContainerRegister
{
    public function register(ContainerPopulatorInterface $populator)
    {
        parent::register($populator);

        $populator
            ->instance(MiddlewareRegisterInterface::class, MiddlewareRegister::class)
            ->instance(ActionMiddlewareRegisterInterface::class, ActionMiddlewareRegister::class)

            ->initialize(AbstractProcessor::class, function(AbstractProcessor $processor){
                $processor->validate();
            });
    }
}