<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaterialController extends AbstractController
{
    /**
     * @Route("/material", name="material_list", methods={"GET"})
     */
    public function material_list(ItemRepository $itemRepository): Response
    {
        $items = $itemRepository->findAll();

        return $this->render('material/material-list.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/material/pdf", name="material_list_pdf", methods={"GET"})
     */
    public function material_list_pdf(ItemRepository $itemRepository, Pdf $knpSnappyPdf): Response
    {
        $items = $itemRepository->findAll();

        $html = $this->render('material/material-list.html.twig', [
            'items' => $items,
        ])->getContent();

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'materiaallijst ' . date('Y-m-d h:i:s') . '.pdf'
        );
    }
}
