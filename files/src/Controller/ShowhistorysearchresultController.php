<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProdHistoryRepository;

class ShowhistorysearchresultController extends AbstractController
{
    /**
     * @Route("/showhistorysearchresult", name="showhistorysearchresult")
     */
    public function index(ProdHistoryRepository $ProdHistoryRepository, Request $request): Response
    {
        if(isset($_GET['prodId'])) {
            $prodId = $_GET['prodId'];
        }else{
            $prodId = null;
        }
        if(isset($_GET['prodSerial'])) {
            $prodSerial = $_GET['prodSerial'];
        }else{
            $prodSerial = null;
        }
        
        $results = $ProdHistoryRepository->searchby($prodId,$prodSerial);

        $searchform = $this->createFormBuilder(null)
            ->add('prodId')
            ->add('prodSerial')
            ->getForm();
    
        $searchform->handleRequest($request);
    
        if ($searchform->isSubmitted() && $searchform->isValid()) {
            $data = $searchform->getData();
                
            return $this->redirectToRoute('showhistorysearchresult', $data);
        }
    
        return $this->render('showhistorysearchresult/index.html.twig', [
            'ProdId' => $results,
            'searchform' => $searchform->createView()
        ]);
    }
}