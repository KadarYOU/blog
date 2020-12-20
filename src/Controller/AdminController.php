<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditProfilType;
use App\Controller\UserRepository;
use App\Repository\UsersRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as UserPasswordEncoder;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function usersList(UsersRepository $users)
    {

        // dd($test);
        return $this->render('admin/users.html.twig');
    }

    /**
     * @Route("/profil", name="profil_user")
     */
    public function profil()
    {
        return $this->render('admin/profil.html.twig');
    }

    /**
     * @Route("/user/edit_profil", name="edit_profil_user")
     * @IsGranted("ROLE_USER")
     */
    public function editProfil(Request $request, EntityManagerInterface $em, UserPasswordEncoder $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager = $this->getDoctrine()->getManager();
            // $hash = $encoder->encodePassword($user, $user->getPassword());
            // $user->setPassword($hash);
            // dd($user);
            $em->persist($user);


            $em->flush();

            $this->addFlash('message', 'Profil mise à jours');
            return $this->redirectToRoute('utilisateur');
        }

        return $this->render('admin/editProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/editpass", name="edit_pass_user")
     */
    public function editPass(Request $request, EntityManagerInterface $em)
    {
        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            // on verifie si les 2 mot de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass1')) {
            } else {

                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }


        return $this->render('admin/editPass.html.twig');
    }
}
