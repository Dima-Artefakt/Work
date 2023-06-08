<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\ItemCollection;
use App\Entity\Topic;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       $ttt = $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $routeBuilder = $this->get(AdminUrlGenerator::class);
                $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

                return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Work');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'hоme_page');
        yield MenuItem::linkToCrud('User', 'fas fa-map-marker-alt', User::class);
        yield MenuItem::linkToCrud('Item collection', 'fas fa-comments', ItemCollection::class);
        yield MenuItem::linkToCrud('Topic', 'fas fa-comments', Topic::class);
        yield MenuItem::linkToCrud('Comment', 'fas fa-comments', Comment::class);
    }
}
