<?php
namespace ImmediateSolutions\Support\Infrastructure\Doctrine;

use ImmediateSolutions\Support\Framework\ConfigInterface;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Metadata\DescriberInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Describer implements DescriberInterface
{
    /**
     * @var string
     */
    private $appPath;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->appPath = $config->get('app_path');
    }

    /**
     * @param string $package
     * @return string
     */
    public function getEntityNamespace($package)
    {
        return 'ImmediateSolutions\\Core\\' . $package . '\\Entities';
    }

    /**
     * @param string $package
     * @return string
     */
    public function getMetadataNamespace($package)
    {
        return 'ImmediateSolutions\Infrastructure\DAL\\' . $package . '\Metadata';
    }

    /**
     * @param string $package
     * @return string
     */
    public function getEntityPath($package)
    {
        return $this->appPath.'/src/Core/' . str_replace('\\', '/', $package) . '/Entities';
    }

    /**
     * @param string $package
     * @return string
     */
    public function getTypeNamespace($package)
    {
        return 'ImmediateSolutions\Infrastructure\DAL\\' . $package . '\Types';
    }

    /**
     * @param string $package
     * @return string
     */
    public function getTypePath($package)
    {
        return $this->appPath.'/src/Infrastructure/DAL/' . str_replace('\\', '/', $package) . '/Types';
    }
}