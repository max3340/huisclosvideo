<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminVideoController extends AbstractController
{

    /**
     * @var VideoRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(VideoRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/videos", name="admin.video.index")
     * @return Response
     */

    public function index()
    {
        $videos = $this->repository->findAll();
        return $this->render('admin/video/index.html.twig', compact('videos'));
    }


    /**
     * @Route("/admin/video/create", name="admin.video.new")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->persist($video);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé :)');
            return $this->redirectToRoute('admin.video.index');
        }

        return $this->render('admin/video/new.html.twig', [
            'video' => $video,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/video/edit/{id}", name="admin.video.edit", methods="GET|POST")
     * @param Video $video
     * @param Request $request
     * @return Response
     */

    public function edit(Video $video, Request $request)
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié :)');
            return $this->redirectToRoute('admin.video.index');
        }

        return $this->render('admin/video/edit.html.twig', [
            'video' => $video,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Video $video
     * @param Request $request
     * @return RedirectResponse
     * @Route("admin/video/{id}", name="admin.video.delete", methods="DELETE")
     */

    public function delete(Video $video, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->get('_token')))
            $this->em->remove($video);
        $this->em->flush();
        $this->addFlash('success', 'Vidéo bien supprimée :)');
        return $this->redirectToRoute('admin.video.index');
    }
}