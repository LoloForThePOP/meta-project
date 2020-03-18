<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Slide;
use App\Entity\PPBasic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
    }

    
    public function load(ObjectManager $manager)
    {

        $faker= Factory::create('fr-FR');

        // User Creation

        $users=[];
        $userGenres=['male','female'];
        
        for($i=1; $i<=10; $i++) {

            $user = new User();

            // User Image Creation

            $imageUrlBegin="https://randomuser.me/api/portraits/";
            $userGenre = $faker->randomElement($userGenres);
            $imageUrlEnd="/".$faker->numberBetween(1,99).'.jpg';
            $image=$imageUrlBegin.$userGenre.$imageUrlEnd;

            // User Hash Creation

            $hash=$this->encoder->encodePassword($user,'password');

            // User Hydrate

            $user->setName($faker->firstName($userGenre))
                ->setEmail($faker->email())
                ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)). '</p>')
                ->setImage($image)
                ->setHash($hash)
            ;

            $manager->persist($user);
            
            $users[]=$user;
       
        }


        // Project Presentations Creation
        
        for($i=1; $i<=25; $i++) {

            $pp = new PPBasic ();

            // Title Creation
            $title='untitled project';
            $filledTitle=array_rand([true,true,true,true,false]);

            if($filledTitle==true){
                $title=$faker->sentence();
            }

            // Keywords Creation
            $keywordsNumber=mt_rand(0,7);
            
            if ($keywordsNumber == 0) {
                $keywords='';
            } else {
                $keywords='#'.join(' #',$faker->words($keywordsNumber));
            }
            
            // Categories Creation
            $categoriesNumber=mt_rand(0,7);
            $categories=''.join(' ',$faker->words($categoriesNumber));

            // Logo Creation
            $logoColors=['000000','ffa500','ff6347','1e90ff','6a5acd','ee82ee','3cb371'];
            $logoColor=$logoColors[array_rand($logoColors)];
            $logoURL='https://place-hold.it/64x64/'.$logoColor.'&text=PjctLogo&bold';

            // Thumbnail Creation
            $thumbnailURL='https://place-hold.it/1000x400/';

            // Presentation Creator Creation
            $creator=$users[ mt_rand(0, count($users)-1) ];
            
            $pp ->setTitle($title)
                ->setGoal($faker->paragraph())
                ->setKeywords($keywords)
                ->setThumbnail($thumbnailURL)
                ->setLogo($logoURL)
                ->setCreatedAt($faker->dateTime())
                ->setCreator($creator)
                ->setCategories($categories);

            // Slides Creation

            for($j=1;$j<=mt_rand(3,5);$j++){
                
                $slide=new Slide();

                // Media Type Creation
                $mediaTypes = ['image','image','image','video'];
                $mediaType = $mediaTypes[array_rand($mediaTypes)];
                
                // URL Creation
                $url='';
                if ($mediaType == 'image') {
                    $imageColors=['000000','ffa500','ff6347','1e90ff','6a5acd','ee82ee','3cb371'];
                    $imageColor=$imageColors[array_rand($imageColors)];
                    $url='https://place-hold.it/500x300/'.$imageColor;
                } else if ($mediaType=='video') {
                    $url='https://www.youtube.com/watch?v=XQu8TTBmGhA';
                }

                $slide  
                    ->setMediaType($mediaType)
                    ->setUrl($url)
                    ->setCaption($faker->sentence())
                    ->setPP($pp)
                ;

                $manager->persist($slide);
            }


            $manager->persist($pp);

        }

        $manager->flush();
    }
}
