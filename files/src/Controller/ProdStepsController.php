<?php

namespace App\Controller;

use App\Entity\ProdSteps;
use App\Form\ProdStepsType;
use App\Repository\ProdStepsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prod/steps")
 */
class ProdStepsController extends AbstractController
{
    /**
     * @Route("/", name="prod_steps_index", methods={"GET"})
     */
    public function index(): Response
    {
        $prodSteps = $this->getDoctrine()
            ->getRepository(ProdSteps::class)
            ->findAll();

        return $this->render('prod_steps/index.html.twig', [
            'prod_steps' => $prodSteps,
        ]);
    }

    /**
     * @Route("/new", name="prod_steps_new", methods={"GET","POST"})
     */
    public function new(Request $request, ProdStepsRepository $ProdStepsRepository): Response
    {
        $prodStep = new ProdSteps();
        $form = $this->createForm(ProdStepsType::class, $prodStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prodStep);
            $entityManager->flush();

            return $this->redirectToRoute('prod_steps_index'); 
        }

        return $this->render('prod_steps/new.html.twig', [
            'prod_step' => $prodStep,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prod_steps_show", methods={"GET"})
     */
    public function show(ProdSteps $prodStep): Response
    {
        return $this->render('prod_steps/show.html.twig', [
            'prod_step' => $prodStep,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prod_steps_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProdSteps $prodStep,ProdStepsRepository $ProdStepsRepository): Response
    {
        $form = $this->createForm(ProdStepsType::class, $prodStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prod_steps_index');
        }

        return $this->render('prod_steps/edit.html.twig', [
            'prod_step' => $prodStep,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prod_steps_delete", methods={"POST"})
     */
    public function delete(Request $request, ProdSteps $prodStep): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prodStep->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prodStep);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prod_steps_index');
    }
}
