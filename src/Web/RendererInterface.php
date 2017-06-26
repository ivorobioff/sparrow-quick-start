<?php
namespace ImmediateSolutions\Support\Web;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface RendererInterface
{
    /**
     * @param string $path
     * @param array $parameters
     * @return string
     */
    public function render($path, array $parameters = []);
}