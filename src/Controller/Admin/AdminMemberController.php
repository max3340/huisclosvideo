<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMemberController extends AbstractController
{

    /**
     * @var MemberRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(MemberRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/membres", name="admin.member.index")
     * @return Response
     */

    public function index()
    {
        $membres = $this->repository->findAll();
        return $this->render('admin/member/index.html.twig', compact('membres'));
    }


    /**
     * @Route("/admin/membres/create", name="admin.member.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $membre = new Member();
        $form = $this->createForm(MemberType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($membre);
            $this->em->flush();
            $this->addFlash('success', 'Membre bien créé :)');
            return $this->redirectToRoute('admin.member.index');
        }

        return $this->render('admin/member/new.html.twig', [
            'members' => $membre,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/membres/edit/{id}", name="admin.member.edit", methods="GET|POST")
     * @param Member $membre
     * @param Request $request
     * @return Response
     */

    public function edit(Member $membre, Request $request)
    {
        $form = $this->createForm(MemberType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Membre Bien modifié :)');
            return $this->redirectToRoute('admin.member.index');
        }

        return $this->render('admin/member/edit.html.twig', [
            'members' => $membre,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Member $membre
     * @param Request $request
     * @return RedirectResponse
     * @Route("admin/membres/{id}", name="admin.member.delete", methods="DELETE")
     */

    public function delete(Member $membre, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $membre->getId(), $request->get('_token')))
            $this->em->remove($membre);
        $this->em->flush();
        $this->addFlash('success', 'Membre bien supprimé :)');
        return $this->redirectToRoute('admin.member.index');
    }
}