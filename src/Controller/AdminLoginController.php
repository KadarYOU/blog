<?php

namespace App\Controller;



use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminLoginController extends AbstractController
{

  /**
   * @Route("/admin/login", name="admin_login")
   */
  public function Login(AuthenticationUtils $utils)
  {

    $error = $utils->getLastAuthenticationError();
    $username = $utils->getLastUsername();
    // dd($username);

    return $this->render('admin/login_admin.html.twig', [
      'hasError' => $error !== null,
      'username' => $username
    ]);
  }

  /**
   * Permet de se déconnecter
   * 
   * @Route("admin/logout", name="admin_logout")
   *
   * @return void
   */
  public function logout()
  {
    // .. rien !
  }

  // /**
  //  * Permet d'éditer un articles
  //  * 
  //  * @Route("/admin/article/{id}/edit", name="admin_article_edit")
  //  * 
  //  * @return Response
  //  */
  // public function edit(Article $article, Request $request, EntityManagerInterface $manager)
  // {
  //   $form = $this->createForm(ArticleType::class, $article);

  //   $form->handleRequest($request);
  //   #group de validation : https://symfony.com/doc/current/validation/groups.html
  //   if ($form->isSubmitted() && $form->isValid()) {
  //     $article->setContent(0);

  //     $manager->persist($article);
  //     $manager->flush();

  //     $this->addFlash(
  //       'success',
  //       "La réservation n°{$article->getId()} a bien été modifiée"
  //     );

  //     return $this->redirectToRoute("home");
  //   }

  //   return $this->render('blog/create.html.twig', [
  //     'formArticle' => $form->createView(),
  //     'editMode' => $article->getId() !== null
  //   ]);
  // }
  // /**
  //  * Permet de supprimer un article
  //  * 
  //  * @Route("/admin/article/{id}/delete", name="admin_booking_delete")
  //  *
  //  * @return Response
  //  */
  // public function delete(Article $article, EntityManagerInterface $manager)
  // {
  //   $manager->remove($article);
  //   $manager->flush();

  //   $this->addFlash(
  //     'success',
  //     "La réservation a bien été supprimée"
  //   );

  //   return $this->redirectToRoute("home");
  // }
}
