<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ItemCollection;
// use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile/{idUser}', name: 'profile')]
    public function index($idUser,AuthenticationUtils $authenticationUtils,EntityManagerInterface $em, $edit = false): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $itemUser = $em->getRepository(User::class)->findOneBy(['id' => $idUser]);

        $itemsCollectionUser = $em->getRepository(ItemCollection::class)
        ->findBy(
            ['user' =>  $itemUser],
            ['createDate' => 'DESC']
        );

        // $form = $this->createForm(EditUserType::class, $user);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'error' => $error,'itemUser' => $itemUser,
            'itemsCollectionUser' => $itemsCollectionUser,
            'edit' => $edit,
        ]);
    }
}
