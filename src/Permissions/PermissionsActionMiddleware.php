<?php
namespace ImmediateSolutions\Support\Permissions;

use ImmediateSolutions\Support\Framework\Action;
use ImmediateSolutions\Support\Framework\ActionMiddlewareInterface;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use ImmediateSolutions\Support\Framework\Exceptions\ForbiddenHttpException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PermissionsActionMiddleware implements ActionMiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Action $action
     * @param callable $next
     * @return ResponseInterface
     */
    public function handle(Action $action, callable $next)
    {
        $protectable = $action->getController();

        if (!$protectable instanceof ProtectableInterface){
            return $next($action);
        }

        $class = $this->getClass($protectable);

        if (!class_exists($class)) {
            throw new PermissionsException('The permissions class "' . $class . '" has not been found.');
        }

        $definition = $this->container->get($class);

        if (!$definition instanceof AbstractActionsPermissions) {
            throw new PermissionsException('The permissions class "' . $class . '" must be instance of AbstractPermissions.');
        }

        /**
         * @var PermissionsInterface $permissions
         */
        $permissions = $this->container->get(PermissionsInterface::class);

        if (!$permissions->has($definition->getProtectors($action->getName()))) {
            throw new ForbiddenHttpException(ProtectableInterface::ACCESS_DENIED);
        }

        return $next($action);
    }

    /**
     * @param ProtectableInterface $protectable
     * @return string
     */
    private function getClass(ProtectableInterface $protectable)
    {
        $parts = explode('\\', get_class($protectable));
        $name = array_pop($parts);

        return (implode('\\', $parts).'\Permissions\\'.cut_string_right($name, 'Controller').'Permissions');
    }
}