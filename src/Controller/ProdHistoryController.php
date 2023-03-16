<?php

namespace App\Controller;

use App\Entity\ProdHistory;
use App\Form\ProdHistoryType;
use App\Repository\ProdHistoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prod/history")
 */
class ProdHistoryController extends AbstractController
{
    /**
     * @Route("/", name="prod_history_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $prodHistories = $this->getDoctrine()
            ->getRepository(ProdHistory::class)
            ->findAll();

            $searchform = $this->createFormBuilder(null)
            ->add('prodId')
            ->add('prodSerial')
            ->getForm();
    
            $searchform->handleRequest($request);
    
            if ($searchform->isSubmitted() && $searchform->isValid()) {
                $data = $searchform->getData();
                
                return $this->redirectToRoute('showhistorysearchresult', $data);
            }

        return $this->render('prod_history/index.html.twig', [
            'prod_histories' => $prodHistories,
            'searchform' => $searchform->createView()
        ]);
    }

    /**
     * @Route("/new", name="prod_history_new", methods={"GET","POST"})
     */
    public function new(Request $request, ProdHistoryRepository $ProdHistoryRepository): Response
    {
        $prodHistory = new ProdHistory();
        $form = $this->createForm(ProdHistoryType::class, $prodHistory);
        $form->handleRequest($request);
        $date = date('Y-m-d', time());

        if ($form->isSubmitted() && $form->isValid()) {
            if($prodHistory->getProdId()!=null&&$prodHistory->getStepid()!=null){
                $check = $ProdHistoryRepository->findOneBy(array('prodId' => $prodHistory->getProdId(),'stepid' => $prodHistory->getStepid(),'prodSerial' => $prodHistory->getProdSerial()));
                if($check==null||$check->getStepstatus()==false){
                    $entityManager = $this->getDoctrine()->getManager();
                    $prodHistory->setTimestamp(\DateTime::createFromFormat('Y-m-d', $date));
                    $entityManager->persist($prodHistory);
                    $entityManager->flush();

                    return $this->redirectToRoute('prod_history_newaftervalidation');
                }else{
                    $timeymd = $check->getTimestamp()->format('d/m/Y');
                    $timehis = $check->getTimestamp()->format('H:i:s');
                    $var = 'The product "'.$prodHistory->getProdId().'" with the serial "'.$prodHistory->getProdSerial().'" has already passed the step "'.$prodHistory->getStepid().'" on the '.$timeymd.' at '.$timehis;
                    echo "<script>alert('$var')</script>";
                }
            }else{
                $var = 'Prodid and/or Stepid values cannot be null!';
                echo "<script>alert('$var')</script>";
            }
        }

        $results = $ProdHistoryRepository->findBy(array(),array('id'=>'DESC'),1,0);
        return $this->render('prod_history/new.html.twig', [
            'prod_history' => $prodHistory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newaftervalidation", name="prod_history_newaftervalidation", methods={"GET","POST"})
     */
    public function newaftervalidation(Request $request, ProdHistoryRepository $ProdHistoryRepository, ProductRepository $ProductRepository): Response
    {
        $prodHistory = new ProdHistory();
        $form = $this->createForm(ProdHistoryType::class, $prodHistory);
        $form->handleRequest($request);
        $date = date('Y-m-d', time());

        if ($form->isSubmitted() && $form->isValid()) {
            $check = $ProdHistoryRepository->findOneBy(array('prodId' => $prodHistory->getProdId(),'stepid' => $prodHistory->getStepid(),'prodSerial' => $prodHistory->getProdSerial()));
            if($check==null||$check->getStepstatus()==false){
                $entityManager = $this->getDoctrine()->getManager();
                $prodHistory->setTimestamp(\DateTime::createFromFormat('Y-m-d', $date));
                $results = $ProdHistoryRepository->findBy(array(),array('id'=>'DESC'),1,0);
                $finalprod = $ProductRepository->findOneBy(array('prodid' => $results[0]->getProdId()));
                $prodHistory->setProdId($finalprod);
                $prodHistory->setStepid($results[0]->getStepid());
                $entityManager->persist($prodHistory);
                $entityManager->flush();

                return $this->redirectToRoute('prod_history_newaftervalidation');
            }else{
                $timeymd = $check->getTimestamp()->format('d/m/Y');
                $timehis = $check->getTimestamp()->format('H:i:s');
                $var = 'The product "'.$prodHistory->getProdId().'" with the serial "'.$prodHistory->getProdSerial().'" has already passed the step "'.$prodHistory->getStepid().'" on the '.$timeymd.' at '.$timehis;
                echo "<script>alert('$var')</script>";
            }
        }

        return $this->render('prod_history/newaftervalidation.html.twig', [
            'prod_history' => $prodHistory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prod_history_show", methods={"GET"})
     */
    public function show(ProdHistory $prodHistory): Response
    {
        return $this->render('prod_history/show.html.twig', [
            'prod_history' => $prodHistory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prod_history_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProdHistory $prodHistory): Response
    {
        $form = $this->createForm(ProdHistoryType::class, $prodHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prod_history_index');
        }

        return $this->render('prod_history/edit.html.twig', [
            'prod_history' => $prodHistory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prod_history_delete", methods={"POST"})
     */
    public function delete(Request $request, ProdHistory $prodHistory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prodHistory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prodHistory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prod_history_index');
    }
}
