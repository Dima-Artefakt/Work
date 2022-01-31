<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class ListController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {

    }

    #[Route('/list', name: 'list')]
    public function show(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->em->getRepository(User::class)->findAll();

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('list/index.html.twig', [
            'users' => $user,'error' => $error
        ]);
    }

    #[Route('/editList/{id}', name: 'editUser')]
    public function editUser($id,Request $request,EntityManagerInterface $em): Response
    {

        $user = new User();
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('list/editUser.html.twig', [
            'EditUserForm' => $form->createView(),
        ]);
    }
    #[Route('/deleteUser/{id}', name: 'deleteUser')]
    public function deleteUser($id,EntityManagerInterface $em): Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('list');
    }
}
