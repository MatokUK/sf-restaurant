<?php

namespace App\Command;


use App\Command\RecordConverter\DataTypeA;
use App\Command\RecordConverter\DataTypeB;
use App\Csv\Reader;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCsvFilesCommand extends Command
{
    protected static $defaultName = 'slido:read:csv';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RestaurantRepository */
    private $restaurantRepository;

    public function __construct(EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository)
    {
        $this->entityManager = $entityManager;
        $this->restaurantRepository = $restaurantRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import data from CSV files.')
            ->setHelp('This command imports data from specific two files located in csv-data folder.')
            ->addOption('truncate', 't', InputOption::VALUE_NONE, 'Truncate data before import?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Import command is running");

        if ($input->getOption('truncate')) {
            $output->writeln('<comment>Truncating data...</comment>');
            $this->restaurantRepository->deleteAll();
        }

        $output->writeln("Importing first file");

        $this->importCsvDataA(__DIR__.'/../../csv-data/restaurants-hours-source-1.csv');
        $this->importCsvDataB(__DIR__.'/../../csv-data/restaurants-hours-source-2.csv');

        $this->entityManager->flush();

        $output->writeln("Import command finished");
    }

    /**
     * @param $filename
     * @throws \App\Csv\Exception\CannotOpenFileException
     * @throws \App\Csv\Exception\FileNotFoundException
     */
    private function importCsvDataA($filename): void
    {
        $csvReader = new Reader($filename, ['skip_header' => true]);
        $type = new DataTypeA();
        foreach ($csvReader->readLines() as $line) {
            $restaurant = $type->createRestaurant($line);
            $this->entityManager->persist($restaurant);
        }
    }

    /**
     * @param $filename
     * @throws \App\Csv\Exception\CannotOpenFileException
     * @throws \App\Csv\Exception\FileNotFoundException
     */
    private function importCsvDataB($filename): void
    {
        $csvReader = new Reader($filename, ['skip_header' => false]);
        $type = new DataTypeB();
        foreach ($csvReader->readLines() as $line) {
            $restaurant = $type->createRestaurant($line);
            $this->entityManager->persist($restaurant);
        }
    }
}