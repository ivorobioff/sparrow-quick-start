<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Api\Verify\VerifiableInterface;
use ImmediateSolutions\Support\Framework\Action;
use ImmediateSolutions\Support\Framework\ActionMiddlewareInterface;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use ImmediateSolutions\Support\Framework\Exceptions\NotFoundHttpException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class VerifyActionMiddleware implements ActionMiddlewareInterface
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
        $verifiable = $action->getController();

        if (!$verifiable instanceof VerifiableInterface){
            return $next($action);
        }

        if ($this->shouldBypass($action, $verifiable)){
            return $next($action);
        }

        if (!method_exists($verifiable, 'verify')) {
            throw new \RuntimeException('The "verify" method is missing even though the controller is verifiable.');
        }

        $method = new \ReflectionMethod($verifiable, 'verify');

        $arguments = $action->getArguments();

        foreach ($method->getParameters() as $index => $argument) {
            $class = $argument->getClass();

            if (!$class) {
                continue;
            }

            $class = $class->getName();

            if ($class === Action::class || is_subclass_of($class, Action::class)) {
                $instance = $action;
            } else {
                $instance = $this->container->get($class);
            }

            array_splice($arguments, $index, 0, [$instance]);
        }

        $result = call_user_func_array([$verifiable, 'verify'], $arguments);

        if (!$result) {
            throw new NotFoundHttpException(VerifiableInterface::NOT_FOUND);
        }

        return $next($action);
    }

    /**
     * @param Action $action
     * @param VerifiableInterface $verifiable
     * @return bool
     */
    private function shouldBypass(Action $action, VerifiableInterface $verifiable)
    {
        if (!$action->getArguments()) {
            return true;
        }

        return $verifiable->shouldVerify() === false;
    }
}