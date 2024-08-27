<?php

namespace Untek\Develop\Package\Commands;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Develop\Package\Domain\Entities\PackageEntity;

class GitBranchCheckoutToRootCommand extends BaseCommand
{

    protected static $defaultName = 'package:git:branch-checkout-to-root';

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
        $output->writeln('<fg=yellow>Total:</>');
        $output->writeln('');
        $output->writeln('<fg=yellow>' . implode("\n", CollectionHelper::getColumn($totalCollection, 'id')) . '</>');
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
            $branches = $this->gitService->branches($packageEntity);
            $branch = $this->gitService->branch($packageEntity);
            $rootBranch = $this->gitService->getRootBranch($packageEntity);
            if ($branch == $rootBranch) {

            } else {
                $output->write(" checkout $rootBranch ... ");
                $this->gitService->checkout($packageEntity, $rootBranch);
                $totalCollection->add($packageEntity);
            }
            $output->writeln("<fg=green>OK</>");
        }
        return $totalCollection;
    }
}
