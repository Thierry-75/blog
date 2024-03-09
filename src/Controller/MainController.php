<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        try {
            $data = $articleRepository->findPublished();
        } catch (EntityNotFoundException $ex) {
            echo "Exception Found - " . $ex->getMessage() . "<br />"; // addflash ?
        }
        return $this->render('pages/main/index.html.twig',
        ['articles'=>$paginatorInterface->paginate($data,$request->query->getInt('page',1),6)]);
    }

    #[Route('/article/{slug}',name:'app_article_show',methods:['GET'])]
    public function showArticle(Article $article): Response
    {
        return $this->render('pages/main/show.html.twig',['article'=>$article]);
    }

}
