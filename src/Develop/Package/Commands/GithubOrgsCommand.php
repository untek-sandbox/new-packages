<?php

namespace Untek\Develop\Package\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Develop\Package\Domain\Entities\GroupEntity;
use Untek\Develop\Package\Domain\Entities\PackageEntity;
use Yiisoft\Arrays\ArrayHelper;

class GithubOrgsCommand extends BaseCommand
{

    protected static $defaultName = 'package:github:orgs';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># github orgs</>');

        $url = 'https://api.github.com/user/orgs?access_token=' . getenv('GITHUB_TOKEN');
        $output->writeln('getting groups');
        $collection = $this->sendRequest('GET', $url);
        $orgs = ArrayHelper::getColumn($collection, 'login');
        $repoCollection = new ArrayCollection();
        foreach ($orgs as $orgName) {
            if (strpos($orgName, 'zn') === 0) {
                $url = "https://api.github.com/orgs/{$orgName}/repos";
                $output->writeln('getting packages from: ' . $orgName);
                $repos = $this->sendRequest('GET', $url);
                $reposList = ArrayHelper::getColumn($repos, 'name');
                $groupEntity = new GroupEntity();
                $groupEntity->name = $orgName;
                $groupEntity->providerName = 'github';
                $orgArr = [
                    'name' => $orgName
                ];
                foreach ($reposList as $repoName) {
                    $packageEntity = new PackageEntity();
                    $packageEntity->setName($repoName);
                    $packageEntity->setGroup($groupEntity);
                    $repoCollection->add($packageEntity);
                }
            }
        }
        $fileName = 'zn/untek-tool/dev/src/Package/Domain/Data/package_origin.php';
        $array = CollectionHelper::toArray($repoCollection);
        $array = ExtArrayHelper::extractItemsWithAttributes($array, ['id', 'name', 'group']);

        $store = new StoreFile($fileName);
        $store->save($array);

        $output->writeln('');
        return 0;
    }

    public function sendRequest(string $method, string $url, array $options = []): array
    {
        $client = new Client();
        try {
            $response = $client->request($method, $url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        return json_decode($response->getBody()->getContents());
    }
}
