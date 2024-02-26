<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i=0;$i<50; $i++){
            $article = new Article();
            $article->setTitle($faker->sentence($nbwords=3, $variableNbWords = true))
                    ->setContent($faker->realText(1000))
                    ->setPublished(mt_rand(0,2) ===1 ? false : true);
            $manager->persist($article);
        }

        $manager->flush();
    }
}
