<?php
namespace ImmediateSolutions\Support\Web;

use League\Plates\Engine;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Renderer implements RendererInterface
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * @param string $viewsPath
     */
    public function __construct($viewsPath)
    {
        $this->engine = new Engine($viewsPath);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @return string
     */
    public function render($path, array $parameters = [])
    {
        return $this->engine->render($path, $parameters);
    }
}