<?php

namespace App\Controller;

use App\Repository\PersonnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Personnel;
use App\Form\PersonnelType;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartJsController extends AbstractController

{
    /**
     * @Route("/chartjs", name="chartjs")
     */
    public function index(PersonnelRepository $personnelRepository, ChartBuilderInterface $chartBuilder): Response
    {

        $personnels = $personnelRepository->findAll();
        $labels = [];
        $data = [];

        foreach ($personnels as $personnel) {
            $labels[] = $personnel-> getPoste();
            $data[] = $personnel->getHAbsence();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(81, 152, 255)',
                    'borderColor' => 'rgb(0, 168, 255)',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([]);

        return $this->render('chart_js/index.html.twig', [
            'controller_name' => 'ChartJsController',
            'chart' => $chart,
        ]);
    }
}