<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditProfilType;
use App\Controller\UserRepository;
use App\Repository\UsersRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentairesRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{

    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function usersList(UsersRepository $users)
    {

        // dd($test);
        return $this->render('Profil/users.html.twig');
    }



    /**
     * Permet de modifier son profil
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

        return $this->render('Profil/editProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * @Route("/user/editpass", name="edit_pass_user")
     */
    public function editPass(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {


        if ($request->isMethod('POST')) {


            $user = $this->getUser();

            // on verifie si les 2 mot de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass1')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'mot de passe mis à jour avec succès');
                return $this->redirectToRoute('utilisateur');
            } else {

                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }


        return $this->render('Profil/editPass.html.twig');
    }
    /**
     * Permet de supprimer un articles
     * @Route("\blog\articles\{id}", name ="remove_article")
     */
    public function remove($id, EntityManagerInterface $em, ArticleRepository $repo, CommentairesRepository $commentaireRespository)
    {
        $comment = $commentaireRespository->find($id);

        if ($comment !== null) {;
            $em->remove($comment);
            $em->flush();
        }

        $article = $repo->find($id);

        // dd($article->getId());
        $em->remove($article);

        $em->flush();
        $this->addFlash('deleteArticle', 'L\'article a bien été supprimer');

        return $this->redirectToRoute('home');
    }
}
