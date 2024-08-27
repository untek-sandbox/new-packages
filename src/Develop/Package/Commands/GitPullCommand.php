<?php

namespace Untek\Develop\Package\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Develop\Package\Domain\Entities\PackageEntity;

class GitPullCommand extends BaseCommand
{

    protected static $defaultName = 'package:git:pull';

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
        $output->writeln('');
        if ($totalCollection->count() == 0) {
            $output->writeln('<fg=magenta>All packages already up-to-date!</>');
            $output->writeln('');
            return 0;
        }
        $this->displayTotal($totalCollection, $input, $output);
        $output->writeln('');
        return 0;
    }

    private function displayProgress(Collection $collection, InputInterface $input, OutputInterface $output): Collection
    {
        /** @var PackageEntity[] | Collection $collection */
        /** @var PackageEntity[] | Collection $totalCollection */
        $totalCollection = new ArrayCollection();
        foreach ($collection as $packageEntity) {
            $packageId = $packageEntity->getId();
            $output->write(" $packageId ... ");
            $result = $this->gitService->pullPackage($packageEntity);
            if ($result == 'Already up to date.') {
                $result = "<fg=green>{$result}</>";
            } else {
                $totalCollection->add($packageEntity);
            }
            if($result) {
                $output->writeln($result);
            }
        }
        return $totalCollection;
    }

    private function displayTotal(Collection $totalCollection, InputInterface $input, OutputInterface $output)
    {
        /** @var PackageEntity[] | Collection $totalCollection */
        $output->writeln('<fg=yellow>Updated packages!</>');
        $output->writeln('');
        foreach ($totalCollection as $packageEntity) {
            $packageId = $packageEntity->getId();
            $output->writeln("<fg=yellow> {$packageId}</>");
        }
    }
}
