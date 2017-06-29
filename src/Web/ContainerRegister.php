<?php
namespace ImmediateSolutions\Support\Web;

use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\ContainerRegisterInterface;
use League\Plates\Engine;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ContainerRegister implements ContainerRegisterInterface
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
     * @param ContainerPopulatorInterface $populator
     */
    public function register(ContainerPopulatorInterface $populator)
    {
        $populator
            ->instance(Engine::class, new EngineFactory($this->viewsPath))
            ->service(RendererInterface::class, Renderer::class);
    }
}