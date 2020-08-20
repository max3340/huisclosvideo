<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * @param CategoryRepository $repository
     * @return Response
     */
    public function index(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();
        return $this->render('home/home.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @param CategoryRepository $repository
     * @return Response
     * @Route("/portfolio", name="home.portfolio")
     */
    public function portfolio(CategoryRepository $repository): Response
    {
        $catgories = $repository->findAll();
        return $this->render('home/portfolio.html.twig', [
            'categories' => $catgories
        ]);

    }

    /**
     * @param MemberRepository $repository
     * @return Response
     * @Route("/equipe", name="home.equipe")
     */
    public function equipe(MemberRepository $repository): Response
    {
        $members = $repository->findAll();
        return $this->render('home/equipe.html.twig', [
            'members' => $members
        ]);

    }


    /**
     * @return Response
     * @Route("/contact", name="home.contact")
     */
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');

    }

}

