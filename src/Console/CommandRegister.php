<?php
namespace ImmediateSolutions\Support\Console;

use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use ImmediateSolutions\Support\Framework\CommandRegisterInterface;
use ImmediateSolutions\Support\Framework\CommandStorageInterface;
use ImmediateSolutions\Support\Framework\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CommandRegister implements CommandRegisterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param CommandStorageInterface $storage
     */
    public function register(CommandStorageInterface $storage)
    {
        $commands = [
            new UpdateCommand(),
            new CreateCommand(),
            new DropCommand(),
            new GenerateProxiesCommand(),
            new GenerateCommand(),
            new MigrateCommand(),
            new DiffCommand(),
            new ExecuteCommand()
        ];

        foreach ($commands as $command){
            $storage->add($this->withinDoctrine($command));
        }
    }

    /**
     * @param Command $command
     * @return Command
     */
    private function withinDoctrine(Command $command)
    {
        $command->setHelperSet(new HelperSet([
            'em' => new EntityManagerHelper($this->container->get(EntityManagerInterface::class))
        ]));

        return $command;
    }
}