<?php
namespace ImmediateSolutions\Support\Web;

use ImmediateSolutions\Support\Framework\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, callable $next)
    {
        session_start();

        return $next($request);
    }
}