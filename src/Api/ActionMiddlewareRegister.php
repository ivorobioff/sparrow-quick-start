<?php
namespace ImmediateSolutions\Support\Api;
use ImmediateSolutions\Support\Framework\ActionMiddlewareRegisterInterface;
use ImmediateSolutions\Support\Framework\MiddlewarePipeline;
use ImmediateSolutions\Support\Permissions\PermissionsMiddleware;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ActionMiddlewareRegister implements ActionMiddlewareRegisterInterface
{
    /**
     * @param MiddlewarePipeline $pipeline
     */
    public function register(MiddlewarePipeline $pipeline)
    {
        $pipeline->add(PermissionsMiddleware::class);
    }
}