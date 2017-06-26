<?php
namespace ImmediateSolutions\Support\Web;

use ImmediateSolutions\Support\Framework\ContainerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class RendererFactory
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

    /**
     * @param ContainerInterface $container
     * @return RendererInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        return new Renderer($this->viewsPath);
    }
}