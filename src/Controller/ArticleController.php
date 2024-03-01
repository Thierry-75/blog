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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController
{


    private  $parameters = 'photo_directory';


    #[Route('/article-new', name: 'app_article_new',methods:['GET','POST'])]
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
                foreach ($photos as $photo) {
                    $imageService->addPhoto($photo,$this->parameters, $article);
                }
            }
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            $this->addFlash('success', "l'article : " . $article->getTitle() . " a été enregistré");
            return $this->redirectToRoute('app_main_index');
        }
        return $this->render('pages/article/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/article-edit/{id}',name:'app_article_edit',methods:['GET','POST'])]
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
                foreach ($photos as $photo) {
                    $imageService->addPhoto($photo, $this->parameters, $article);
                }
            }
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            $this->addFlash('success', "l'article : " . $article->getTitle() . "a été modifié");
            return $this->redirectToRoute('app_main_index');
        }
        return $this->render('pages/article/edit.html.twig', ['form' => $form->createView(),'article'=>$article]);
    }


     #[Route('/supprime/image/{id}', name:'articles_delete_image',methods:['DELETE'])]
    public function deleteImage(Photo $image, Request $request,EntityManagerInterface $entityManagerInterface){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete', $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getUrl();
            // On supprime le fichier
            unlink($this->getParameter('photo_directory').'/'.$nom);

            // On supprime l'entrée de la base
            
            $entityManagerInterface->remove($image);
            $entityManagerInterface->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }



}
