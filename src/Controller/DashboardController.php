<?php

namespace App\Controller;

use App\Repository\SoutenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Soutenance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    #[Route('/dashboard/enseignant', name: 'dashboard_enseignant')]
    #[IsGranted('ROLE_ENSEIGNANT')]
    public function enseignant(SoutenanceRepository $repo): Response
    {

/** @var \App\Entity\User $user */
$user = $this->getUser();
$enseignant = $user->getEnseignant();
        $soutenances = $repo->findBy(['enseignant' => $enseignant]);

      return $this->render('enseignant/dashboard.html.twig', [
    'enseignant'  => $enseignant,
    'soutenances' => $soutenances,
]);
    }

    #[Route('/dashboard/etudiant', name: 'dashboard_etudiant')]
    #[IsGranted('ROLE_ETUDIANT')]
    public function etudiant(SoutenanceRepository $repo): Response
    {
        /** @var \App\Entity\User $user */
$user = $this->getUser();
$etudiant = $user->getEtudiant();
        $soutenance = $repo->findOneBy(['etudiant' => $etudiant]);

       return $this->render('etudiant/dashboard.html.twig', [
    'etudiant'   => $etudiant,
    'soutenance' => $soutenance,
]);
    }
    #[Route('/dashboard/enseignant/noter/{id}', name: 'noter_etudiant', methods: ['POST'])]
#[IsGranted('ROLE_ENSEIGNANT')]
public function noter(
    Soutenance $soutenance,
    Request $request,
    EntityManagerInterface $em
): Response {
    // Vérification du token CSRF
    if (!$this->isCsrfTokenValid('noter', $request->request->get('_token'))) {
        throw $this->createAccessDeniedException('Token CSRF invalide.');
    }

    $note = $request->request->get('note');
    $soutenance->setNote((float) $note);
    $em->flush();

    $this->addFlash('success', 'Note enregistrée avec succès !');

    return $this->redirectToRoute('dashboard_enseignant');
}
}