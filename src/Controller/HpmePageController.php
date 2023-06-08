<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ItemCollection;
use App\Form\ItemCollectionFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Topic;

class HpmePageController extends AbstractController
{
    #[Route('/', name: 'hоme_page')]
    public function index(AuthenticationUtils $authenticationUtils,Request $request,EntityManagerInterface $em): Response
    {
        // $error = $authenticationUtils->getLastAuthenticationError();
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $itemsTopic = $em->getRepository(Topic::class)->findAll();
        foreach( $itemsTopic as $key=> $val){
            $itemsCollectionTopic[] = $em->getRepository(ItemCollection::class)
            ->findBy(
                ['topic' =>  $val],
                ['createDate' => 'DESC']
            );
        };
        arsort($itemsCollectionTopic);
        $itemsCollection = $em->getRepository(ItemCollection::class)->findBy(
            [],
            ['createDate' => 'DESC']
        );;
        
        return $this->render('hpme_page/homePage.html.twig', [
            'controller_name' => 'HоmePageController',
            // 'error' => $error,
            'itemsCollection' => $itemsCollection,
            'itemsTopic' => $itemsTopic,
            'itemsCollectionTopic' => $itemsCollectionTopic
        ]);
    }

    #[Route('/abaut', name: 'abaut')]
    public function abaut(): Response
    {   
        return $this->render('hpme_page/abaut.html.twig', [
            'controller_name' => 'abaut',
        ]);
    }
}
