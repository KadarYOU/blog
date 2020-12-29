<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SecurityCoontrollerController extends AbstractController
{

    /**
     * Permet de faire l'inscription
     * @Route("/inscription",name="inscription_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoder $encoder)
    {
        //on instancie l'objets Users
        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        // on verifier si les formulaires est soumit et tout les champs sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // on a hasher(cache) les mdp dans le base de donnée
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            // on prepare les données 
            $manager->persist($user);
            // on envoie au base de donnée
            $manager->flush();
            //on fait la redirection  au page de connexion
            return $this->RedirectToRoute('security_login');
        }
        return $this->render(
            'security_controller/registration.html.twig',
            [
                'form_insp' => $form->CreateView()
            ]
        );
    }
    /**
     * Permet de se connecter au site
     * @Route("/connexion",name="security_login")
     */
    public function connexion()
    {

        return $this->render('security_controller/login.html.twig');
    }
    /**
     * Permet de se deconnecter au site
     * @Route("/deconnexion",name="security_logout")
     */
    public function deconnexion()
    {
    }
}
