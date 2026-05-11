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
public function enseignant(SoutenanceRepository $repo, EntityManagerInterface $em): Response
{
    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    $enseignant = $user->getEnseignant();
    $soutenances = $repo->findBy(['enseignant' => $enseignant]);

    // Mettre à jour l'état automatiquement
    foreach ($soutenances as $soutenance) {
        if ($soutenance->getEtat() == 'a_venir' && $soutenance->getDateSoutenance() < new \DateTime()) {
            $soutenance->setEtat('passee');
        }
    }
    $em->flush();

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
    $soutenances = $repo->findBy(['etudiant' => $etudiant]);

    return $this->render('etudiant/dashboard.html.twig', [
        'etudiant'    => $etudiant,
        'soutenances' => $soutenances,
    ]);
}
 #[Route('/dashboard/enseignant/noter/{id}', name: 'noter_etudiant', methods: ['POST'])]
#[IsGranted('ROLE_ENSEIGNANT')]
public function noter(
    Soutenance $soutenance,
    Request $request,
    EntityManagerInterface $em
): Response {
    // Bloquer si date pas encore passée
    if ($soutenance->getDateSoutenance() > new \DateTime()) {
        $this->addFlash('error', 'Impossible de noter avant la date de soutenance !');
        return $this->redirectToRoute('dashboard_enseignant');
    }

    if (!$this->isCsrfTokenValid('noter', $request->request->get('_token'))) {
        throw $this->createAccessDeniedException('Token CSRF invalide.');
    }

    $soutenance->setNote((float) $request->request->get('note'));
    $soutenance->setEtat('notee');
    $em->flush();

    $this->addFlash('success', 'Note enregistrée !');
    return $this->redirectToRoute('dashboard_enseignant');
}
}