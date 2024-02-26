<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article-new', name: 'app_article_new')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($request->isMethod("POST")) {
            $article->setPublished(true);
            $errors = $validatorInterface->validate($article);
            if (count($errors) > 0) {
                return $this->render('pages/article/new.html.twig', ['form' => $form->createView(), 'errors' => $errors]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManagerInterface->persist($article);
                $entityManagerInterface->flush();
                $this->addFlash('success', "l'article : " . $article->getTitle() . " a été enregistré");
                return $this->redirectToRoute('app_main_index');
            }
        }
        return $this->render('pages/article/new.html.twig', ['form' => $form->createView()]);
    }
}
