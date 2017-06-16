<?php
namespace ImmediateSolutions\Support\Permissions;

use ImmediateSolutions\Support\Framework\Action;
use ImmediateSolutions\Support\Framework\ActionMiddlewareInterface;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use ImmediateSolutions\Support\Framework\Exceptions\ForbiddenHttpException;
use Psr\Http\Message\ResponseInterface;

/**
 * The middleware checks the permissions based on the requested controller and action.
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PermissionsMiddleware implements ActionMiddlewareInterface
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
        $controller = $action->getController();
        $method = $action->getName();

        if (!$controller){
            return $next($action);
        }

        if (!$controller instanceof ProtectableInterface){
            return $next($action);
        }

        $class = $this->getClass($controller);

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

        if (!$permissions->has($definition->getProtectors($method))) {
            throw new ForbiddenHttpException();
        }

        return $next($action);
    }

    /**
     * @param object $controller
     * @return string
     */
    private function getClass($controller)
    {
        $parts = explode('\\', get_class($controller));
        $name = array_pop($parts);

        return (implode('\\', $parts).'\Permissions\\'.cut_string_right($name, 'Controller').'Permissions');
    }
}