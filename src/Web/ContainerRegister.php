<?php
namespace ImmediateSolutions\Support\Web;

use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\ContainerRegisterInterface;

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
        $populator->service(RendererInterface::class, new RendererFactory($this->viewsPath));
    }
}