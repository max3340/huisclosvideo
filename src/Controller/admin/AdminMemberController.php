<?php

namespace App\Controller\Admin;

use App\Entity\Members;
use App\Form\MemberType;
use App\Repository\MembersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMemberController extends AbstractController
{

    /**
     * @var MembersRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(MembersRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/membres", name="admin.membres.index")
     * @return Response
     */

    public function index()
    {
        $membres = $this->repository->findAll();
        return $this->render('admin/membres/index.html.twig', compact('membres'));
    }


    /**
     * @Route("/admin/membres/create", name="admin.membres.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $membre = new Members();
        $form = $this->createForm(MemberType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($membre);
            $this->em->flush();
            $this->addFlash('success', 'Membre bien créé :)');
            return $this->redirectToRoute('admin.membres.index');
        }

        return $this->render('admin/membres/new.html.twig', [
            'members' => $membre,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/membres/edit/{id}", name="admin.membres.edit", methods="GET|POST")
     * @param Members $membre
     * @param Request $request
     * @return Response
     */

    public function edit(Members $membre, Request $request)
    {
        $form = $this->createForm(MemberType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Membre Bien modifié :)');
            return $this->redirectToRoute('admin.membres.index');
        }

        return $this->render('admin/membres/edit.html.twig', [
            'members' => $membre,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Members $membre
     * @param Request $request
     * @return RedirectResponse
     * @Route("admin/membres/{id}", name="admin.membres.delete", methods="DELETE")
     */

    public function delete(Members $membre, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $membre->getId(), $request->get('_token')))
            $this->em->remove($membre);
        $this->em->flush();
        $this->addFlash('success', 'Membre bien supprimé :)');
        return $this->redirectToRoute('admin.membres.index');
    }
}