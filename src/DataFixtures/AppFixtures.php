<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\City;
use App\Entity\Need;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Slide;
use App\Entity\Contact;
use App\Entity\PPBasic;
use App\Entity\Website;
use App\Entity\Category;
use App\Entity\ContactMessage;
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

        // a Role Creation: Admin

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // a User with Admin Role Creation

        $adminUser = new User();
        $adminUser->setName('Lolo')
                ->setEmail('lolo@symfony.com')
                ->setHash($this->encoder->encodePassword($adminUser,'password'))
                ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)). '</p>')
                ->addUserRole($adminRole);

        $manager->persist($adminUser);

        // Project Categories Creation

        $categories = [
            'material' => "Construction, Matériel",
            'education' => "Informer, Eduquer, Apprendre",
            'science' => "Science, Recherche",
            'food' => "Agriculture, Nourriture",
            'ideas' => "Idées, Politique",
            'environment' => "Environnement (Protection)",
            'arts' => "Arts, Culture",
            'software' => "Codage, Internet",
            'humanitary' => "Vivre Ensemble, Humanitaire",
            'health' => "Santé",
            'crisis' => "Crise",
            'entertain' => "Divertissement",
            'history' => "Histoire, Patrimoine",
        ];

        $categoriesObjects = [];

        $index=0;
        foreach ($categories as $key => $value){
            $category = new Category();
            $category->setName($key);
            $category->setDescriptionFr($value);
            $category->setIcon('https://place-hold.it/24x24/');
            $manager->persist($category);

            $categoriesObjects[]=$category;
            $index++;
        }

        

        // Users Creation

        $users=[];
        $userGenres=['men','women'];
        
        for($i=1; $i<=30; $i++) {

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
                ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(4)). '</p>')
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
            $title='Projet Sans Titre';
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

            // Logo Creation
            $logoColors=['ffa500','ff6347','1e90ff','6a5acd','ee82ee','3cb371'];

            $logoColor=$logoColors[array_rand($logoColors)];
            $logoURL='https://place-hold.it/64x64/'.$logoColor.'&text=Logo&bold';

            // Thumbnail Creation

            $thumbnailColors=['ffa500','ff6347','1e90ff','6a5acd','ee82ee','3cb371'];

            $thumbnailColor=$thumbnailColors[array_rand($thumbnailColors)];
            
            $thumbnailURL='https://place-hold.it/200x200/'.$thumbnailColor.'&text=Thumbnail&bold';

            // Presentation Creator Creation
            $creator=$users[ mt_rand(0, count($users)-1) ];
            

            // Hydrating Project Presentation with Above Attributes
            $pp ->setTitle($title)
                ->setGoal($faker->paragraph())
                ->setKeywords($keywords)
                ->setThumbnail($thumbnailURL)
                ->setLogo($logoURL)
                ->setCreatedAt($faker->dateTime())
                ->setCreator($creator);

            // Project Cities (with Postal Codes) Creation

            $numPostalCodes = mt_rand(0,3);

            if ($numPostalCodes > 0){

                for ($j=1; $j<=$numPostalCodes; $j++){
                
                    $city = new City();

                    $postalCode = mt_rand(10000,99000);

                    $city -> setPostalCode ($postalCode)
                          -> setCityName($faker->words(1)[0]);

                    $pp->addCity($city);

                    $manager->persist($city);
                }

            }

            // Project Websites Creation

            $numWebsites = mt_rand(0,3);

            if ($numWebsites > 0){

                for ($j=1; $j<=$numWebsites; $j++){
                
                    $website = new Website();

                    $url = $faker->url();
                    $description =  $faker->sentence();

                    $website -> setUrl ($url)
                            -> setDescription($description)
                            -> setPosition($j)
                            -> setPresentation($pp);

                    $manager->persist($website);
                }

            }

            // Contact Messages Creation

            $hasMessages = array_rand([true,true,true,true,false]);

            if ($hasMessages){

                $numContactMessages = mt_rand(1,30);

                for ($j=1; $j<=$numContactMessages; $j++){
                
                    $contactMessage = new ContactMessage();

                    $hasBeenConsulted = array_rand([true,true,false]);

                    $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                    $context = $faker->sentence();
                    $title = $faker->sentence();
                    $content = '<p>'.join('</p><p>',$faker->paragraphs(4)). '</p>';
                    $senderEmail = $faker -> email();
                    $receiver = $pp->getCreator();

                    $contactMessage -> setContext ($context)
                                    -> setHasBeenConsulted ($hasBeenConsulted)
                                    -> setCreatedAt($createdAt)
                                    -> setTitle($title)
                                    -> setContent($content)
                                    -> setSenderEmail($senderEmail)
                                    -> setPresentation($pp)
                                    -> addReceiver($receiver);

                    $manager->persist($contactMessage);
                }

            }



                        
            // Project Categories Creation
            
            $numCat = mt_rand(0,4); // random number of categories for this project

            if ($numCat>0){ //si le projet a des catégories, on en prend au hasard et on lui ajoute
               $catRandKeys = array_rand($categoriesObjects,$numCat);
               if ($numCat==1){
                   $pp->addCategories($categoriesObjects[$numCat]);
               }
               else {
                   foreach ($catRandKeys as $index){
                       $pp->addCategories($categoriesObjects[$index]);
                   }                 
               }
            }

            // Slides Creation

            $hasSlidesOdds = [true,true,false];

            $hasSlides = $hasSlidesOdds [array_rand($hasSlidesOdds)];

            if ($hasSlides){
                
                for($j=1;$j<=mt_rand(1,4);$j++){
                    
                    $slide=new Slide();

                    // Media Type Creation (only images for the moment)

                    $mediaTypes = ['image','image','image','image'];
                    $mediaType = $mediaTypes[array_rand($mediaTypes)];

                    $imageAddress=null;

                    if($mediaType=="image"){

                        $imagesColors=['ffa500','ff6347','1e90ff','6a5acd','ee82ee','3cb371'];
                        $imageColor=$imagesColors[array_rand($imagesColors)];

                        $imageAdress = 'https://place-hold.it/500x400/'.$imageColor.'&text=ImageExample&bold';
                    }

                    $slide  
                        ->setMediaType($mediaType)
                        ->setCaption($faker->sentence())
                        ->setSlideName($imageAdress)
                        ->setPP($pp)
                    ;

                    $manager->persist($slide);
                }
            }

            

            // Contact Us Boxes Creation

            for($j=1;$j<=mt_rand(0,4);$j++){
                
                $contact = new Contact();

                // we fill a contact title or not (ex: chief officer)

                $titlePossibilities = [null, $faker->sentence($nbWords = 3, $variableNbWords = true) ];
                $title = $titlePossibilities[array_rand($titlePossibilities)];

                // we fill some email fields for this contact, or not

                $emailsPossibilities = [null, $faker->email()];
                $email1 = $emailsPossibilities[array_rand($emailsPossibilities)];
                $email2 = $emailsPossibilities[array_rand($emailsPossibilities)];

                // are displayed emails or not (note : always true at the moment)

                $showEmailPossibilities = [true, true];
                $showEmail =  $showEmailPossibilities[array_rand($showEmailPossibilities)];

                // we fill telephone fields or not

                $telephonesPossibilities = [null, $faker->phoneNumber()];
                $tel1 = $telephonesPossibilities[array_rand($telephonesPossibilities)];
                $tel2 = $telephonesPossibilities[array_rand($telephonesPossibilities)];

                // we fill websites for this contact, or not

                $websitePossibilities = [null, $faker->url()];
                $website1 = $websitePossibilities[array_rand($websitePossibilities)];
                $website2 = $websitePossibilities[array_rand($websitePossibilities)];

                // we fill a postal mail for this contact, or not

                $postalMailPossibilities = [null, $faker->address()];
                $postalMail = $postalMailPossibilities[array_rand($postalMailPossibilities)];

                // we add some remarks to this contact (ex: opening hours), or not

                $remarksPossibilities = [null, $faker->text($maxNbChars = 90)];
                $remarks = $remarksPossibilities[array_rand($remarksPossibilities)];

                $contact  
                    ->setTitle($title)
                    ->setEmail1($email1)
                    ->setEmail2($email2)
                    ->setShowEmails($showEmail)
                    ->setTel1($tel1)
                    ->setTel2($tel2)
                    ->setWebsite1($website1)
                    ->setWebsite2($website2)
                    ->setPostalMail($postalMail)
                    ->setRemarks($remarks)
                    ->setPresentation($pp)
                ;

                $manager->persist($contact);
            }


            // Desired Ressources Creation

            for($j=1;$j<=mt_rand(0,8);$j++){

                $dr = new Need();

                $needTitle = $faker->sentence();

                $needDescription = '<p>'.join('</p><p>',$faker->paragraphs(5)). '</p>';

                $createdAt = $faker->dateTimeBetween($startDate = '-4 years', $endDate = 'now');

                // Set a Need Priority
                $priorityPossibilities = ['','','priority'];
                $needPriority = $priorityPossibilities[array_rand($priorityPossibilities)];
                
                // Set a Need Type
                $typePossibilities = ['materials','skill','money','advice'];
                $needType = $typePossibilities[array_rand($typePossibilities)];

                // Is it a Paid Ressource?
                $paiementPossibilities = ['yes','maybe','no'];
                $isPaid = $paiementPossibilities[array_rand($paiementPossibilities)];

                $dr ->setTitle($needTitle)
                    ->setDescription($needDescription)
                    ->setPosition($j)
                    ->setCreatedAt($createdAt)
                    ->setPriority($needPriority)
                    ->setType($needPriority)
                    ->setPaidService($isPaid)
                    ->setPresentation($pp);

                $manager->persist($dr);
            }


            $manager->persist($pp);

        }

        $manager->flush();
    }
}