<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    #[Route('/item', name: 'item')]
    public function index(Request $request,EntityManagerInterface $em): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemFormType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($item);
            $em->flush();
        }
        return $this->render('collection/item.html.twig', [
            'itemForm' => $form->createView(),
        ]);
    }
}
