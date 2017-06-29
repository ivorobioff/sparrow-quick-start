<?php
namespace ImmediateSolutions\Support\Web;

use League\Plates\Engine;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class EngineFactory
{
    /**
     * @var string
     */
    private $viewsPath;

    /**
     * @param string $viewsPath
     */
    public function __construct($viewsPath)
    {
        $this->viewsPath = $viewsPath;
    }

    public function __invoke()
    {
        return new Engine($this->viewsPath);
    }
}