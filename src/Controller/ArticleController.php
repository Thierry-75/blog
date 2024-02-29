<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Photo;
use App\Form\ArticleType;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    #[Route('/article-new', name: 'app_article_new')]
    public function newArticle(Request $request, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface, ImageService $imageService): Response
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
                $photos = $form->get('photo')->getData();
                $parameter = 'photo_directory';
                foreach ($photos as $photo) {
                    $imageService->addPhoto($photo, $parameter, $article);
                }
            }
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            $this->addFlash('success', "l'article : " . $article->getTitle() . " a été enregistré");
            return $this->redirectToRoute('app_main_index');
        }
        return $this->render('pages/article/new.html.twig', ['form' => $form->createView()]);
    }


    public function editArticle(Request $request, Article $article, EntityManagerInterface $entityManagerInterface, ValidatorInterface $validatorInterface, ImageService $imageService): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($request->isMethod("POST")) {
            $errors = $validatorInterface->validate($article);
            if (count($errors) > 0) {
                return $this->render('pages/article/edit.html.twig', ['form' => $form->createView(), 'errors' => $errors]);
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $photos = $form->get('photo')->getData();
                $parameter = 'photo_directory';
                foreach ($photos as $photo) {
                    $imageService->addPhoto($photo, $parameter, $article);
                }
            }
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            $this->addFlash('success', "l'article : " . $article->getTitle() . "a été modifié");
            return $this->redirectToRoute('app_main_index');
        }
        return $this->render('pages/article/edit.html.twig', ['form' => $form->createView()]);
    }
}
