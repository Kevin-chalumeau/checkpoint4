<?php

namespace App\Controller;

use App\Entity\Summary;
use App\Form\SummaryType;
use App\Repository\SummaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/summary")
 */
class SummaryController extends AbstractController
{
    /**
     * @Route("/", name="summary_index", methods={"GET"})
     */
    public function index(SummaryRepository $summaryRepository): Response
    {
        return $this->render('summary/index.html.twig', [
            'summaries' => $summaryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="summary_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $summary = new Summary();
        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($summary);
            $entityManager->flush();

            return $this->redirectToRoute('summary_index');
        }

        return $this->render('summary/new.html.twig', [
            'summary' => $summary,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="summary_show", methods={"GET"})
     */
    public function show(Summary $summary): Response
    {
        return $this->render('summary/show.html.twig', [
            'summary' => $summary,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="summary_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Summary $summary): Response
    {
        $form = $this->createForm(SummaryType::class, $summary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('summary_index');
        }

        return $this->render('summary/edit.html.twig', [
            'summary' => $summary,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="summary_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Summary $summary): Response
    {
        if ($this->isCsrfTokenValid('delete'.$summary->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($summary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('summary_index');
    }
}
