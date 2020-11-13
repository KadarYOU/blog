<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityCoontrollerController extends AbstractController
{

    /**
     * @Route("/inscription",name="inscription_registration")
     */
    public function registration()
    {
        //on instancie l'objets Users
        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user);
        return $this->render('security_coontroller/registration.html.twig', [
            'formReg' => $form->CreateView()
        ]);
    }
}
