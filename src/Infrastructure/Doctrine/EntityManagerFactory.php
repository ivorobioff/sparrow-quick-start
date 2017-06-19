<?php
namespace ImmediateSolutions\Support\Infrastructure\Doctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ImmediateSolutions\Support\Framework\ConfigInterface;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\DBAL\Types\Type;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Metadata\CompositeDriver;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Metadata\DescriberInterface;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Metadata\PackageDriver;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Metadata\SimpleDriver;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use RuntimeException;


/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class EntityManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EntityManagerInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ConfigInterface $config
         */
        $config = $container->get(ConfigInterface::class);


        if (!$config->has('doctrine')){
            throw new RuntimeException('Unable to instantiate entity manager due to missing doctrine configuration.');
        }

        /**
         * @var DescriberInterface $describer
         */
        $describer = $container->get(DescriberInterface::class);

        $doctrine = $config->get('doctrine');
        $packages = $config->get('packages', []);

        $em = EntityManager::create(
            $doctrine['connections'][$doctrine['db']],
            $this->createConfiguration($doctrine, $packages, $describer)
        );

        $this->registerTypes(
            $describer,
            $em->getConnection(),
            $packages,
            array_get($doctrine, 'types', [])
        );

        return $em;
    }

    /**
     * @param array $config
     * @param array $packages
     * @param DescriberInterface $describer
     * @return Configuration
     */
    private function createConfiguration(array $config, array $packages, DescriberInterface $describer)
    {
        $setup = Setup::createConfiguration();

        $cache = new $config['cache']();

        $setup->setMetadataCacheImpl($cache);
        $setup->setQueryCacheImpl($cache);

        $setup->setProxyDir($config['proxy']['dir']);
        $setup->setProxyNamespace($config['proxy']['namespace']);
        $setup->setAutoGenerateProxyClasses(array_get($config, 'proxy.auto', false));

        $setup->setMetadataDriverImpl(new CompositeDriver([
            new PackageDriver($packages, $describer),
            new SimpleDriver(array_get($config, 'entities', []))
        ]));

        $setup->setNamingStrategy(new UnderscoreNamingStrategy());
        $setup->setDefaultRepositoryClassName(DefaultRepository::class);

        $driver = $config['connections'][$config['db']]['driver'];

        if ($driver == 'pdo_sqlite'){
            $setup->addCustomDatetimeFunction('YEAR', \DoctrineExtensions\Query\Sqlite\Year::class);
            $setup->addCustomDatetimeFunction('MONTH', \DoctrineExtensions\Query\Sqlite\Month::class);
        } else if ($driver == 'pdo_mysql'){
            $setup->addCustomDatetimeFunction('YEAR', \DoctrineExtensions\Query\Mysql\Year::class);
            $setup->addCustomDatetimeFunction('MONTH', \DoctrineExtensions\Query\Mysql\Month::class);
        } else {
            throw new RuntimeException('Unable to add functions under unknown driver "'.$driver.'".');
        }

        return $setup;
    }

    /**
     * @param DescriberInterface $describer
     * @param Connection $connection
     * @param array $packages
     * @param array $extra
     */
    private function registerTypes(DescriberInterface $describer, Connection $connection, array $packages, array $extra = [])
    {
        foreach ($packages as $package) {
            $path = $describer->getTypePath($package);
            $typeNamespace = $describer->getTypeNamespace($package);

            if (! file_exists($path)) {
                continue;
            }

            $finder = new Finder();

            /**
             *
             * @var SplFileInfo[] $files
             */
            $files = $finder->in($path)
                ->files()
                ->name('*.php');

            foreach ($files as $file) {
                $name = cut_string_right($file->getFilename(), '.php');

                $typeClass = $typeNamespace . '\\' . $name;

                if (! class_exists($typeClass)) {
                    continue;
                }

                if (Type::hasType($typeClass)) {
                    Type::overrideType($typeClass, $typeClass);
                } else {
                    Type::addType($typeClass, $typeClass);
                }

                $connection->getDatabasePlatform()->registerDoctrineTypeMapping($typeClass, $typeClass);
            }
        }

        foreach ($extra as $type){
            if (Type::hasType($type)) {
                Type::overrideType($type, $type);
            } else {
                Type::addType($type, $type);
            }
        }
    }
}