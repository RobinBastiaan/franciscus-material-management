<?php

namespace App\Controller;

use App\Repository\ItemRepository;
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
}
