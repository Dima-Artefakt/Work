<?php

namespace App\Controller;

use App\Entity\ItemCollection;
use App\Entity\Topic;
use App\Entity\Comment;
use App\Form\ItemCollectionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class CollectionController extends AbstractController
{
    #[Route('/collectionAdd', name: 'collectionAdd')]
    public function index(Request $request,EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $collection = new ItemCollection();
        $form = $this->createForm(ItemCollectionFormType::class, $collection);
        $form->handleRequest($request);            

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img')->getData();
            if ($imgFile) {
                $brochureFileName = $fileUploader->upload($imgFile);
                $collection->setImg($brochureFileName);
            }
            $collection ->setUser($user);

            $em->persist($collection);
            $em->flush();
            return $this->redirectToRoute('collectionPage',['id' => $collection->getId()]);
        }
        return $this->render('collectionsItems/index.html.twig', 
            [
                'collectionForm' => $form->createView(),
            ]
        );
    }

    #[Route('/collectionPage/addComment/{id}', name: 'collectionPageAddComment')]
    public function collectionPageComment($id,EntityManagerInterface $em, Request $request){
        $user = $this->getUser();
        $itemPageCollection = $em->getRepository(ItemCollection::class)->findOneBy(['id' => $id]);
        $comment = $request->get('commentText');
        if($comment){
            $commentClass = new Comment();

            $commentClass->setUser($user);
            $commentClass->setItemCollection($itemPageCollection);
            $commentClass->setText($comment);
    
            $em->persist($commentClass);
            $em->flush();
        }
        
        $comments = $em->getRepository(Comment::class)
        ->findBy(
            ['itemCollection' =>  $id],
            ['createdAt' => 'DESC']
        );

        return $this->render('collectionsItems/_pageComent.html.twig', [
            'controller_name' => 'collectionPageComment',
            'comments' => $comments,
            'itemPageCollection' => $itemPageCollection,
        ]);
    }

    #[Route('/collectionPage/{id}', name: 'collectionPage')]
    public function collectionPage($id,AuthenticationUtils $authenticationUtils, EntityManagerInterface $em): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $itemPageCollection = $em->getRepository(ItemCollection::class)->findOneBy(['id' => $id]);
        $comments = $em->getRepository(Comment::class)
        ->findBy(
            ['itemCollection' =>  $id],
            ['createdAt' => 'DESC']
        );

        $itemsTopic =  $em->getRepository(Topic::class)->findAll();

        return $this->render('collectionsItems/page.html.twig', [
            'controller_name' => 'collectionPage',
            'error' => $error,
            'itemPageCollection' => $itemPageCollection,
            'comments' => $comments,
            'itemsTopic' => $itemsTopic,
        ]);
    }

    #[Route('/topic/{id}', name: 'topic')]
    public function Topic($id,EntityManagerInterface $em): Response
    {
        $topic = $em->getRepository(Topic::class)->findOneBy(['id' => $id]);
        $collectionTopic = $em->getRepository(ItemCollection::class)
            ->findBy(
                ['topic' => $id]
            );

        return $this->render('collectionsItems/topic.html.twig', [
            'controller_name' => 'topic',
            'topic' => $topic,
            'collectionTopic' => $collectionTopic,
        ]);
    }
}
