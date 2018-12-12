<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\JobService;

class PagesController extends AbstractController
{
    /**
     * @Route("/pages", name="pages")
     */


    public function index(JobService $jobService)
    {
      $jobOffers = $jobService->getJobOffers();

      return $this->render('pages/index.html.twig', [
          'offers' => $jobOffers,
      ]);
    }
}
