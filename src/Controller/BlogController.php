<?php

namespace App\Controller;

use DateTime;
use App\Entity\Users;
use App\Entity\Article;

use App\Entity\Commentaires;

use App\Form\CommentairesFormType;
use App\Repository\UsersRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {


        $articles = $repo->findAll();



        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles

        ]);
    }
    /**
     * @Route("/", name="home")
     */

    public function home()
    {
        return $this->render('blog/home.html.twig');
    }
    /**
     * @Route("/blog/new",name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function create(Article $article = null, Request $request, ObjectManager $manager)
    {

        if (!$article) {
            $article = new Article;
        }

        $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content', CKEditorType::class)
            ->add('Image')
            ->getform();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->SetCreatedAt(new \DateTime());
            }


            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }



    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(ArticleRepository $repo, Request $request, $id, ObjectManager $manager)
    {
        $article = $repo->find($id);
        // on instancie l'entite commentaires
        $commentaire = new Commentaires();

        // on cree un objet formulaire
        $form = $this->createForm(CommentairesFormType::class, $commentaire);
        // on va recupérer les données saisies
        $form->handleRequest($request);
        // on va vérifie si le formulaire a été envoyé si les données sont valides 
        if ($form->isSubmitted() && $form->isValid()) {
            // ici le formulaire a été envoyer et les données sont valides
            $commentaire->SetArticle($article);
            $commentaire->SetCreatedAT(new \DateTime('now'));
            // on instancie le doctrine 
            // $doctrine = $this->getDoctrine()->getManager();
            // on prepare notre base de données
            $manager->persist($commentaire);

            // on envoie les données au base de données
            $manager->flush();

            // faire la redirection 
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'commentaireForm' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/profile/{id}", name="profil")
     */
    public function profil(UsersRepository $repo, Request $request, $id, ObjectManager $manager)
    {
        $user = $repo->findAll();
        // dd($user);
        return $this->render('blog/profil.html.twig', [
            'profilUser' => $user
        ]);
    }
}
