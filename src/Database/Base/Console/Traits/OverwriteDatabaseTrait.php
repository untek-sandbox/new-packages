<?php

namespace Untek\Database\Base\Console\Traits;

use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Untek\Component\Text\Helpers\TextHelper;
use Untek\Core\Container\Helpers\ContainerHelper;

trait OverwriteDatabaseTrait
{

    /*protected function defaultExclideHosts(): array {
        return [
//            'localhost',
//            '127.0.0.1',
        ];
    }*/

    protected function defaultExclideDatabaseNames(): array
    {
        return [
//            'localhost:*_test',
//            'localhost:*',
//            "pgsql://postgres:postgres@localhost/social_server",
//            'pgsql://localhost/social_server',
//            'pgsql://localhost/*',


//            "*://*@localhost/*_test",
//            "*://localhost/*_test",
//            "sqlite:*test*",

//            "*://*localhost/*",
//            'sqlite:*',
        ];
    }

    protected function isExcludeDatabaseNames(string $database): bool
    {
        $exclude = getenv('DATABASE_PROTECT_EXCLUDE') ?: null;
        $exclude = !empty($exclude) ? explode(',', $exclude) : $this->defaultExclideDatabaseNames();
        foreach ($exclude as $ex) {
            $ex = trim($ex);
            if ($ex == $database || fnmatch($ex, $database)) {
                return true;
            }
        }
        return false;
    }

    protected function getConnectionUrl(): string
    {
        /** @var Manager $manager */
        $manager = ContainerHelper::getContainer()->get(Manager::class);
        $connection = $manager->getConnection();

        $params = [
            'scheme' => $connection->getConfig('driver'),
            'host' => $connection->getConfig('host') ?: 'localhost',
            'port' => $connection->getConfig('port'),
            'path' => $connection->getConfig('database'),
            'user' => $connection->getConfig('username'),
            'pass' => $connection->getConfig('password'),
        ];

        if ($connection->getConfig('driver') == 'sqlite') {
            unset($params['host']);
            unset($params['user']);
            unset($params['pass']);
        }

        $url = $this->generateUrlFromParams($params);
        if ($connection->getConfig('driver') == 'sqlite') {
            $url = TextHelper::removeDoubleChar($url, '/');
        }

        return $url;
    }

    public function generateUrlFromParams(array $data): string
    {
        $url = '';
        if (!empty($data['scheme'])) {
            $url .= $data['scheme'] . '://';
        }
        if (!empty($data['user'])) {
            $url .= $data['user'];
            if (!empty($data['pass'])) {
                $url .= ':' . $data['pass'];
            }
            $url .= '@';
        }
        if (!empty($data['host'])) {
            $url .= $data['host'];
        }
        if (!empty($data['port'])) {
            $url .= ':' . $data['port'];
        }
        if (!empty($data['path'])) {
            $data['path'] = ltrim($data['path'], '/');
            $url .= '/' . $data['path'];
        }
        if (!empty($data['query'])) {
            if (is_array($data['query'])) {
                $data['query'] = http_build_query($data['query']);
            }
            $url .= '?' . $data['query'];
        }
        if (!empty($data['fragment'])) {
            $url .= '#' . $data['fragment'];
        }
        return $url;
    }

    protected function isContinue(InputInterface $input, OutputInterface $output): bool
    {
        $url = $this->getConnectionUrl();
        if ($this->isExcludeDatabaseNames($url)) {
            return true;
        }

        $output->writeln('');
        $output->writeln("Connection URL: <fg=green>{$url}</>");
        $output->writeln('');
        $output->writeln('Further actions may overwrite your database!');
        $question = new ConfirmationQuestion('Do you want to continue? (y|N): ', false);
        $helper = $this->getHelper('question');
        $isContinue = $helper->ask($input, $output, $question);
        $output->writeln('');
        return $isContinue;
    }
}
