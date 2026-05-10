<?php

namespace App\Controller;

use App\Entity\Soutenance;
use App\Form\SoutenanceType;
use App\Repository\SoutenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/soutenance')]
final class SoutenanceController extends AbstractController
{
    #[Route(name: 'app_soutenance_index', methods: ['GET'])]
    public function index(SoutenanceRepository $soutenanceRepository): Response
    {
        return $this->render('soutenance/index.html.twig', [
            'soutenances' => $soutenanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_soutenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $soutenance = new Soutenance();
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($soutenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('soutenance/new.html.twig', [
            'soutenance' => $soutenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soutenance_show', methods: ['GET'])]
    public function show(Soutenance $soutenance): Response
    {
        return $this->render('soutenance/show.html.twig', [
            'soutenance' => $soutenance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_soutenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Soutenance $soutenance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('soutenance/edit.html.twig', [
            'soutenance' => $soutenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_soutenance_delete', methods: ['POST'])]
    public function delete(Request $request, Soutenance $soutenance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soutenance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($soutenance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_soutenance_index', [], Response::HTTP_SEE_OTHER);
    }
}
