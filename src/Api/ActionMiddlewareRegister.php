<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ActionMiddlewareRegisterInterface;
use ImmediateSolutions\Support\Framework\MiddlewarePipeline;
use ImmediateSolutions\Support\Permissions\PermissionsActionMiddleware;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ActionMiddlewareRegister implements ActionMiddlewareRegisterInterface
{
    public function register(MiddlewarePipeline $pipeline)
    {
        $pipeline
            ->add(VerifyActionMiddleware::class)
            ->add(PermissionsActionMiddleware::class);
    }
}