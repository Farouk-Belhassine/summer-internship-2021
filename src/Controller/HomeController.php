<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProdHistoryRepository;
use App\Entity\ProdHistory;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ProdHistoryRepository $ProdHistoryRepository): Response
    {
        $onlyprods = $ProdHistoryRepository->onlyprod();
        $productswithtotal = $ProdHistoryRepository->currentproduction();
        $stepsNtotals = $ProdHistoryRepository->stepsNtotals();

        $step = [];
        $color = [];
        $total_ok = [];
        $total_nok = [];
        foreach($stepsNtotals as $item) {
            $step[] = $item['stepid'];
            $color[] = $item['color'];
            $total_ok[] = ($item['total_ok']*100)/($item['total_ok']+$item['total_nok']);
            $total_nok[] = ($item['total_nok']*100)/($item['total_ok']+$item['total_nok']);
        }

        return $this->render('home/index.html.twig', [
            'Products' => $productswithtotal,
            'Onlyprods' => $onlyprods,
            'stepslist' => json_encode($step),
            'colorlist' => json_encode($color),
            'total_ok' => json_encode($total_ok),
            'total_nok' => json_encode($total_nok)
        ]);
    }

    /**
     * @Route("/home/{prodId}", name="home_showproductstats", methods={"GET"})
     */
    public function showproductstats($prodId, ProdHistoryRepository $ProdHistoryRepository): Response
    {
        $stepsNtotals = $ProdHistoryRepository->stepsNtotalsForOne($prodId);

        $step = [];
        $color = [];
        $total_ok = [];
        $total_nok = [];
        $okvartotal = 0;
        $nokvartotal = 0;
        foreach($stepsNtotals as $item) {
            $okvartotal += $item['total_ok'];
            $nokvartotal += $item['total_nok'];
        }
        foreach($stepsNtotals as $item) {
            $step[] = $item['stepid'];
            $color[] = $item['color'];
            if($okvartotal!=0){
                $total_ok[] = ($item['total_ok']*100)/($okvartotal);
            }else{
                $total_ok[] = 0;
            }
            if($nokvartotal!=0){
                $total_nok[] = ($item['total_nok']*100)/($nokvartotal);
            }else{
                $total_nok[] = 0;
            }
        }

        return $this->render('home/showproductstats.html.twig', [
            'product' => $prodId,
            'stepsNtotals' => $stepsNtotals,
            'stepslist' => json_encode($step),
            'colorlist' => json_encode($color),
            'total_ok' => json_encode($total_ok),
            'total_nok' => json_encode($total_nok),
        ]);
    }
}