<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;



class DownloadController extends AbstractController
{
    #[Route('/download', name: 'app_download')]
    public function index(): JsonResponse
    {
        $session = new Session();
        $session->start();
        dd($session->get('url'));

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DownloadController.php',
        ]);
    }
}
