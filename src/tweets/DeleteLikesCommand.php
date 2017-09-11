<?php namespace Tweets;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteLikesCommand extends Command
{
    use Helpers;

    protected $config = [];
    protected $connector;

    protected function configure()
    {
        $this->setName('tweets:delete-likes')
             ->addArgument('file', InputArgument::OPTIONAL, 'Path to the file to process')
             ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Set the offset to start with', 0)
             ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Set the limit to work on', 10);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $filePath = __DIR__ . '/../../config/config.yml';

        $this->checkFileExistence($filePath, $output);


        try {
            $this->config = Yaml::parse(file_get_contents($filePath))['Tweets'];
        } catch (ParseException $e) {
            $output->writeln(sprintf('Unable to parse the YAML string: %s', $e->getMessage()));
            exit;
        }
        $this->getAuthenticator();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('To be implemented later.'));
    }
}
