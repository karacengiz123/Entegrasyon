<?php

namespace App\Controller;

use App\Entegrasyon\Processor\GittiGidiyorProcessor;
use App\Entegrasyon\Processor\TrendyolProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GittiGidiyorController extends AbstractController
{
    /**
     * @Route("/gitti-gidiyor-create-application", name="gitti_gidiyor_create_application")
     * @param GittiGidiyorProcessor $gittiGidiyorProcessor
     * @return Response
     * @throws \SoapFault
     */
    public function createApplication(GittiGidiyorProcessor $gittiGidiyorProcessor)
    {
        $createApplication = $gittiGidiyorProcessor->createApplication();
        dump($createApplication);
        exit();
    }

    /**
     * @Route("/gitti-gidiyor-get-sale_service", name="gitti_gidiyor_get_sale_service")
     * @param GittiGidiyorProcessor $gittiGidiyorProcessor
     * @return Response
     * @throws \SoapFault
     */
    public function getSaleService(GittiGidiyorProcessor $gittiGidiyorProcessor)
    {
        $getSaleService = $gittiGidiyorProcessor->getSaleService();
        dump($getSaleService);
        exit();
    }

    /**
     * @Route("/gitti-gidiyor-get-products", name="gitti_gidiyor_get_products")
     * @param GittiGidiyorProcessor $gittiGidiyorProcessor
     * @return Response
     * @throws \SoapFault
     */
    public function getProducts(GittiGidiyorProcessor $gittiGidiyorProcessor)
    {
        $getProducts = $gittiGidiyorProcessor->getProducts();
        dump($getProducts);
        exit();
    }


}
