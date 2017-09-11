<?php namespace Tweets;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\Console\Output\OutputInterface;

trait Helpers
{
    private function getAuthenticator()
    {
        $this->connector = new TwitterOAuth(
            $this->config['CONSUMER_KEY'],
            $this->config['CONSUMER_SECRET'],
            $this->config['ACCESS_TOKEN'],
            $this->config['ACCESS_TOKEN_SECRET']
        );
    }

    private function checkFileExistence($filePath, OutputInterface $output)
    {
        if (!file_exists($filePath)) {
            $output->writeln(sprintf('Please make sure that you have the required file: %s', $filePath));
            exit;
        }

        return true;
    }
}
