<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\ManagerBundle\ContaoManager\ApiCommand;

use Contao\ManagerBundle\Api\Application;
use Contao\ManagerBundle\Dotenv\DotenvDumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class SetDotEnvCommand extends Command
{
    private readonly string $projectDir;

    public function __construct(Application $application)
    {
        parent::__construct();

        $this->projectDir = $application->getProjectDir();
    }

    protected function configure(): void
    {
        $this
            ->setName('dot-env:set')
            ->setDescription('Writes a parameter to the .env file.')
            ->addArgument('key', InputArgument::REQUIRED, 'The variable name')
            ->addArgument('value', InputArgument::REQUIRED, 'The new value')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = Path::join($this->projectDir, '.env');

        $dumper = new DotenvDumper($path.'.local');
        $dumper->setParameter($input->getArgument('key'), $input->getArgument('value'));
        $dumper->dump();

        if (!file_exists($path)) {
            (new Filesystem())->touch($path);
        }

        return 0;
    }
}
