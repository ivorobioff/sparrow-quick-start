<?php
namespace ImmediateSolutions\Support\Api;

use ImmediateSolutions\Support\Framework\ContainerInterface;
use ImmediateSolutions\Support\Framework\EnvironmentInterface;
use ImmediateSolutions\Support\Framework\Exceptions\AbstractHttpException;
use ImmediateSolutions\Support\Framework\MiddlewareInterface;
use ImmediateSolutions\Support\Validation\Error;
use ImmediateSolutions\Support\Validation\ErrorsThrowableCollection;
use Psr\Http\Message\ServerRequestInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var EnvironmentInterface
     */
    private $environment;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->responseFactory = $container->get(ResponseFactoryInterface::class);

        if ($container->has(LoggerInterface::class)){
            $this->logger = $container->get(LoggerInterface::class);
        }

        if ($container->has(EnvironmentInterface::class)){
            $this->environment = $container->get(EnvironmentInterface::class);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, callable $next)
    {
        try {
            $response = $next($request);
        } catch (AbstractHttpException $ex) {
            $response = $this->writeHttpException($ex);
        } catch (ErrorsThrowableCollection $errors){

            $data = [];

            /**
             * @var Error[] $e
             */
            foreach ($errors as $property => $error){
                $data[$property] = $this->prepareError($error);
            }

            $response = $this->responseFactory->create(['errors' => $data], 422);

        } catch(Exception $exception) {

            if ($this->logger){
                $this->logger->critical($exception);
            }

            if ($this->environment && $this->environment->isDevelopment()){
                $message = (string) $exception;
            } else {
                $message = 'Internal Server Error';
            }

            $response = $this->writeException(500, $message);
        }

        return $response;
    }

    /**
     * @param AbstractHttpException $exception
     * @return ResponseInterface
     */
    private function writeHttpException(AbstractHttpException $exception)
    {
        return $this->writeException($exception->getCode(), $exception->getMessage());
    }

    /**
     * @param int $code
     * @param string $message
     * @return ResponseInterface
     */
    private function writeException($code, $message)
    {
        return $this->responseFactory->create([
            'code' => $code,
            'message' => $message
        ], $code);
    }


    /**
     * @param Error $error
     * @return array
     */
    private function prepareError(Error $error)
    {
        $data = [
            'identifier' => $error->getIdentifier(),
            'message' => $error->getMessage(),
            'extra' => []
        ];

        if ($error->hasExtra()){
            foreach ($error->getExtra() as $name => $extra){
                $data['extra'][$name] = $this->prepareError($extra);
            }
        }

        return $data;
    }
}