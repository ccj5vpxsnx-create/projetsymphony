<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role        = $form->get('role')->getData();
            $nom         = $form->get('nom')->getData();
            $prenom      = $form->get('prenom')->getData();
            $identifiant = $form->get('identifiant')->getData();

            // Hash du mot de passe
            $user->setPassword(
                $hasher->hashPassword($user, $form->get('plainPassword')->getData())
            );
            $user->setRoles([$role]);

            if ($role === 'ROLE_ENSEIGNANT') {
                $enseignant = new Enseignant();
                $enseignant->setMatricule((int) $identifiant);
                $enseignant->setNom($nom);
                $enseignant->setPrenom($prenom);
                $enseignant->setUser($user);
                $em->persist($enseignant);
            } else {
                $etudiant = new Etudiant();
                $etudiant->setNce($identifiant);
                $etudiant->setNom($nom);
                $etudiant->setPrenom($prenom);
                $etudiant->setUser($user);
                $em->persist($etudiant);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form,
        ]);
    }
}