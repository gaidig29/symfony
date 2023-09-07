<?php

namespace App\utils;

use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Core\DateTime;

class UpdateSerie
{

    public function __construct(private EntityManagerInterface $entityManager, private SerieRepository $serieRepository)
    {
    }

    public function removeOldSeries(): int
    {
        $cpt = 0;
        $series = $this->serieRepository->findAll();
        $date = new \DateTime("-10 year");
        foreach ($series as $serie) {
            if ($serie->getLastAirDate() < $date) {
                $this->entityManager->remove($serie);
                $cpt++;
            }
        }
        $this->entityManager->flush();
        return $cpt;
    }

}