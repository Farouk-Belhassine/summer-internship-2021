<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }
}