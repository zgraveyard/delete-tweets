<?php namespace Tweets;

use League\Csv\Reader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends Command
{
    use Helpers;

    protected $config = [];
    protected $connector;

    protected function configure()
    {
        $this->setName('tweets:delete')
             ->addArgument('file', InputArgument::OPTIONAL, 'Path to the file to process')
             ->addOption('skip', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Put the id of the tweet to skip')
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
        $filePath = $input->getArgument('file');
        $offset = $input->getOption('offset');
        $limit = $input->getOption('limit');
        $tweetToSkip = $input->getOption('skip');

        if ((int)$limit > 4000) {
            $output->writeln('<error>Bigger limit number cause time out.</error>');
            exit;
        }

        $this->checkFileExistence($filePath, $output);
        $csvFile = $this->readCSVFile($filePath);

        collect($csvFile->getIterator())
            ->reverse()
            ->slice($offset)
            ->take($limit)
            ->each(function ($item) use ($output, $tweetToSkip) {
                if (!in_array($item['tweet_id'], $tweetToSkip, true)) {
                    $result = $this->connector->post('statuses/destroy', ['id' => $item['tweet_id']]);
                    if (property_exists($result, 'text')) {
                        $output->writeln(sprintf(
                            '<comment>[OK]</comment> Deleting: "%s" which was created at: %s',
                            $result->text,
                            $result->created_at
                        ));
                    } else {
                        $output->writeln(sprintf(
                            '<error>[ERR]</error> Tweet with the ID: "%s" has been <error>deleted</error>.',
                            $item['tweet_id']
                        ));
                    }
                } else {
                    $output->writeln(sprintf(
                        '<comment>[NOTE]</comment> Tweet with the ID: "%s" has been skipped.',
                        $tweetToSkip
                    ));
                }
            });

        $output->writeln(sprintf('We have deleted %s tweets.', $limit));
    }

    private function readCSVFile($filePath)
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
