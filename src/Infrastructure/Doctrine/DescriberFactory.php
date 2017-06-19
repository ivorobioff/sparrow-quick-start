<?php
namespace ImmediateSolutions\Support\Infrastructure\Doctrine;

use ImmediateSolutions\Support\Framework\ConfigInterface;
use ImmediateSolutions\Support\Framework\ContainerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class DescriberFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ConfigInterface $config
         */
        $config = $container->get(ConfigInterface::class);

        return new Describer($config->get('app_path'));
    }
}