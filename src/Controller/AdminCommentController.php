<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\AdminCommentType;
use App\Form\CommentairesFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
  /**
   * @Route("/admin/comment", name= "admin_comment")
   */
  public function index(CommentairesRepository $repo)
  {

    return $this->render('admin/comment/comment.html.twig', [
      'comments' => $repo->findAll()
    ]);
  }
  /**
   * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
   */
  public function edit(Commentaires $comment, CommentairesRepository $repo, EntityManagerInterface $em, Request $request)
  {
    $form = $this->createForm(AdminCommentType::class, $comment);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->persist($comment);
      $em->flush();
      $this->addFlash(
        'success',
        "L'annonce <strong>{$comment->getemail()}</strong> a bien été enregistrée !"
      );
    }
    return $this->render('admin/comment/edit.html.twig', [
      'comments' => $comment,
      'form' => $form->CreateView()
    ]);
  }
}
