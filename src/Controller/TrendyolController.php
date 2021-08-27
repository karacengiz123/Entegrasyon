<?php

namespace App\Controller;

use App\Entegrasyon\Processor\TrendyolProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrendyolController extends AbstractController
{
    /**
     * @Route("/trendyol-get-order", name="trendyol_get_order")
     */
    public function index(TrendyolProcessor $trendyolProcessor): Response
    {
        $getAllOrders = $trendyolProcessor->getOrders();

        dump($getAllOrders);
        exit();
    }
}
