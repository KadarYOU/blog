<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{
  /**
   * @Route("/admin/article", name="admin_article")
   */
  public function index(ArticleRepository $repo)
  {
    $article = $repo->findAll();

    return $this->render("admin/article/index.html.twig", [
      "articles" => $article
    ]);
  }
  /**
   * Permet d'afficher le formulaire d'édition
   * 
   * @Route("/admin/article/{id}/edit", name="admin_article_edit")
   *
   * @return Response
   */
  public function edit(Article $article, Request $request, EntityManagerInterface $manager)
  {
    $form = $this->createForm(ArticleType::class, $article);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $manager->persist($article);

      $manager->flush();

      $this->addFlash(
        'success',
        "L'annonce <strong>{$article->getTitle()}</strong> a bien été enregistrée !"
      );
    }

    return $this->render('admin/article/edit.html.twig', [
      'article' => $article,
      'form' => $form->createView()
    ]);
  }
  /**
   * Permet de supprimer une annonce
   *
   * @Route("/admin/article/{id}/delete", name="admin_article_delete")
   * 
   * @return Response
   */
  public function delete(Article $article, EntityManagerInterface $manager)
  {


    $manager->remove($article);
    $manager->flush();

    $this->addFlash(
      'success',
      "L'annonce <strong>{$article->getTitle()}</strong> a bien été supprimée !"
    );


    return $this->redirectToRoute('admin_article');
  }
}
