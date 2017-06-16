<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\MiddlewareRegisterInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ContainerRegister extends \ImmediateSolutions\Support\Infrastructure\ContainerRegister
{
    public function register(ContainerPopulatorInterface $populator)
    {
        parent::register($populator);

        $populator
            ->instance(MiddlewareRegisterInterface::class, MiddlewareRegister::class)
            ->initialize(AbstractProcessor::class, function(AbstractProcessor $processor){
                $processor->validate();
            });
    }
}