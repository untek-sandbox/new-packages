<?php

namespace Untek\Develop\Package\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Develop\Package\Domain\Entities\PackageEntity;

class GitCheckoutCommand extends BaseCommand
{

    protected static $defaultName = 'package:git:checkout';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Packages git pull</>');
        $collection = $this->packageService->findAll();
        $output->writeln('');
        if ($collection->count() == 0) {
            $output->writeln('<fg=magenta>Not found packages!</>');
            $output->writeln('');
            return 0;
        }
        $totalCollection = $this->displayProgress($collection, $input, $output);

//        $output->writeln('');
//        $output->writeln('<fg=yellow>Total:</>');
//        $output->writeln('');
//        $output->writeln('<fg=yellow>' . implode("\n", CollectionHelper::getColumn($totalCollection, 'id')) . '</>');
//        $output->writeln('');

        return 0;
    }

    private function displayProgress(Enumerable $collection, InputInterface $input, OutputInterface $output): Enumerable
    {
        /** @var PackageEntity[] | Enumerable $collection */
        /** @var PackageEntity[] | Enumerable $totalCollection */
        $totalCollection = new Collection();
        foreach ($collection as $packageEntity) {
            $output->writeln("cd {$packageEntity->getDirectory()} && git checkout -b 0.1.x");

//            dd();
//            $packageId = $packageEntity->getId();
//            $output->write(" $packageId ... ");
//            $branches = $this->gitService->branches($packageEntity);
//            $branch = $this->gitService->branch($packageEntity);
//
//            $rootBranch = $this->gitService->getRootBranch($packageEntity);
//            if ($branch == $rootBranch) {
//
//            } else {
//                $output->write(" checkout $rootBranch ... ");
//                $this->gitService->checkout($packageEntity, $rootBranch);
//                $totalCollection->add($packageEntity);
//            }
//            $output->writeln("<fg=green>OK</>");
        }
        return $totalCollection;
    }
}
