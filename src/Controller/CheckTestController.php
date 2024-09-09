<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckTestController extends AbstractController
{
    #[Route('/check/test', name: 'app_check_test', methods: ['POST'])]
    public function index(Request $request): Response
    {
        return $this->render('check_test/index.html.twig', [
            'controller_name' => 'CheckTestController',
        ]);
    }
}
