<?php
namespace ImmediateSolutions\Support\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use ImmediateSolutions\Support\Core\Interfaces\ContainerInterface;
use ImmediateSolutions\Support\Framework\ContainerPopulatorInterface;
use ImmediateSolutions\Support\Framework\ContainerRegisterInterface;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Describer;
use ImmediateSolutions\Support\Infrastructure\Doctrine\EntityManagerFactory;
use ImmediateSolutions\Support\Infrastructure\Doctrine\Metadata\DescriberInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ContainerRegister implements ContainerRegisterInterface
{
    /**
     * @param ContainerPopulatorInterface $populator
     */
    public function register(ContainerPopulatorInterface $populator)
    {
        $populator->service(ContainerInterface::class,
            function(\ImmediateSolutions\Support\Framework\ContainerInterface $container){
            return new Container($container);
        });

        $populator->service(EntityManagerInterface::class, new EntityManagerFactory());
        $populator->instance(DescriberInterface::class, Describer::class);
    }
}