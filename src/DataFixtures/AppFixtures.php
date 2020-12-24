<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Need;
use App\Entity\News;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Owner;
use App\Entity\Slide;
use App\Entity\PGroup;
use App\Entity\Report;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Persorg;
use App\Entity\PPBasic;
use App\Entity\Website;
use App\Entity\Category;
use App\Entity\Document;
use App\Entity\Teammate;
use App\Entity\GeoDomain;
use App\Entity\TechnicalData;
use App\Entity\ContactMessage;
use App\Entity\QuestionAnswer;
use App\Entity\TextDescription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\ExternalContributorsStructure;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
    }

    // return true with a certain probability (to generate randomness)

    public function chance($average = 0.5) {

        return lcg_value() < $average;

    }

    // hydrates a person or organisation object (= persorg) (completes its profile with a name; photo/logo; email; etc)
    
    // possible persorg types are person or organisation

    public function hydratePersorg($type = 'both', $persorgObject) {

        $faker= Factory::create('fr-FR');

        $hasEmail = [null, $faker->email()];
        $email = $hasEmail[array_rand($hasEmail)];
        
        $hasWebdomain1 = [null, $faker->url()];
        $webdomain1 = $hasWebdomain1[array_rand($hasWebdomain1)];
        
        $hasWebdomain2 = [null, $faker->url()];
        $webdomain2 = $hasWebdomain2[array_rand($hasWebdomain2)];
        
        $hasWebdomain3 = [null, $faker->url()];
        $webdomain3 = $hasWebdomain3[array_rand($hasWebdomain3)];
        
        $hasWebdomain4 = [null, $faker->url()];
        $webdomain4 = $hasWebdomain4[array_rand($hasWebdomain4)];
        
        $hasDescription = [null, $faker->paragraph($nbSentences = 3, $variableNbSentences = true)];
        $description = $hasDescription[array_rand($hasDescription)];
        
        $hasMissions = [null, $faker->text($maxNbChars = 80)];
        $missions = $hasMissions[array_rand($hasMissions)];

        /* Image Creation */
                    
        $hasImageOdds = [true, false];
        $hasImage =  $hasImageOdds[array_rand($hasImageOdds)];

        $image= NULL;


        switch ($type) {

            case 'person':

                $name = $faker->name();

                if ($hasImage){

                    $personsGenres=['male','female'];
        
                    $personGenre = $faker->randomElement($personsGenres);
        
                    // Person Image Creation (using randomuser.me api)
        
                    $pictureUrlBegin="https://randomuser.me/api/portraits/";
        
                    $randomGenre=NULL;
        
                    if ($personGenre=='male') {
                        $randomGenre='men';
                    }

                    else {
                        $randomGenre='women';
                    }
                    
                    $pictureUrlEnd="/".mt_rand(1,99).'.jpg';

                    $image=$pictureUrlBegin.$randomGenre.$pictureUrlEnd;

                }

                break;

            case 'organisation':
                
                $name = $faker->company();

                break;
            
            default:
                # code...
                break;
        }

        $persorgObject-> setEmail ($email)
                -> setWebdomain1 ($webdomain1)
                -> setWebdomain2 ($webdomain2)
                -> setWebdomain3 ($webdomain3)
                -> setWebdomain4 ($webdomain4)
                -> setImage ($image)
                -> setDescription ($description)
                -> setMissions ($missions)
                -> setName($name);

        return $persorgObject;

    }

    
    public function load(ObjectManager $manager)
    {

        $faker= Factory::create('fr-FR');

        // Create a website User

        $demoUser = new User();
        $demoUser->setEmail('test@test.com')
                ->setHash($this->encoder->encodePassword($demoUser,'test'));

        $demoUser->getPersorg()->setName('testUser');     
        
        $manager->persist($demoUser);

        // Create an Admin Role

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole); 

        // Create a Master Admin Role 

        $masterAdminRole = new Role();
        $masterAdminRole->setTitle('ROLE_MASTER_ADMIN');
        $manager->persist($masterAdminRole);

        // Create a User with Admin Role

        $adminUser = new User();
        
        $adminUser
                ->setEmail('lolo@symfony.com')
                ->setHash($this->encoder->encodePassword($adminUser,'password'))
                ->addUserRole($adminRole)
                ->addUserRole($masterAdminRole)
            ; 

        $adminUser  ->getPersorg()
                    ->setName('Lolo')
                    ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(5)). '</p>')
            ; 

        $manager->persist($adminUser);


        // Project Categories Creation

        $categories = [

            'software' => "Informatique, Codage, Internet",
            'science' => "Science, Recherche",
            'education' => "Informer, Éduquer, Apprendre",
            'humane' => "Vivre Ensemble, Humanitaire",
            'material' => "Fabriquer, Construire, Rénover",
            'food' => "Agriculture, Nourriture",
            'ideas' => "Idées, Politique",
            'environment' => "Environnement",
            'arts' => "Culture, Arts",
            'health' => "Santé",
            'history' => "Histoire, Patrimoine",
            'data' => "Organiser des données",
            'money' => "Financement, Argent",
            'animals' => "Animaux",
            'space' => "Espace",
            'crisis' => "Crise",
            'locate' => "Géolocaliser",
            'entertain' => "Divertissements, Loisirs, Sports",
            
        ];

        $categoriesObjects = [];

        $index=0;

        foreach ($categories as $key => $value){

            $category = new Category();
            $category->setName($key);
            $category->setDescriptionFr($value);
            $category->setPosition($index);
            $manager->persist($category);

            $categoriesObjects[]=$category;
            $index++;

        }

        

        // Users Creation

        $users=[];
        $userGenres=['male','female'];
        
        for($i=1; $i<=30; $i++) {

            $user = new User();

            // User Hash Creation
            $hash=$this->encoder->encodePassword($user,'password');

            // Is it an allowed user, or a banished user? (functionnality not implemented yet)
            $isAllowed = array_rand([true,true,true,true,true,true,false]);
            
            // A comment if the user is not Allowed
            $isAllowedComment = join($faker->paragraphs(4));

            // User Hydrate

            $user->setHash($hash)
                ->setEmail($faker->email())
                ->setIsAllowed($isAllowed)
                ->setIsAllowedComment($isAllowedComment)
            ;

            AppFixtures::hydratePersorg('person', $user->getPersorg());

            $manager->persist($user);
            
            $users[]=$user;
       
        }

        // Project Presentations Creation

        $projects=[];
        
        for($i=1; $i <=40; $i++) {

            $pp = new PPBasic ();

            // Project Title Creation

            $filledTitle=array_rand([true,true,true,true, false]);

            if($filledTitle==true){
                $title=$faker->sentence();
            }

            $title=$faker->sentence();

            // Project Date of Creation

            $createdAt = $faker->dateTimeBetween($startDate = '-4 years', $endDate = '-3 years');

            // Keywords Creation
            $keywordsNumber=mt_rand(0,7);
            
            if ($keywordsNumber == 0) {
                $keywords='';
            } else {
                $keywords=join(', ',$faker->words($keywordsNumber));
            }        

            // Thumbnail Creation

            $thumbnailColors=['ffa500','ff6347','1e90ff','6a5acd','ee82ee','3cb371'];

            $thumbnailColor=$thumbnailColors[array_rand($thumbnailColors)];
            
            $thumbnailAddress=$thumbnailColor.'.gif';

            // Project Logos Creation

            $choosenLogo=mt_rand(1, 12);
            
            $logoAddress=$choosenLogo.'.svg';

            // Presentation Creator Creation
            $creator=$users[ mt_rand(0, count($users)-1) ];

            // Draft Presentation, or is published?
            $isPublished = array_rand([true,true,true,true,true,true,false]);

            // Are Comments Allowed ?
            $allowComments = array_rand([true,true,true,true,true,false]);

            // Are Private Messages Activated
            $isActiveContactMessages = array_rand([true,true,true,true,false]);

            // Admin Validation of the Presentation (not implemented yet)
            $isAdminValidated = array_rand([true,true,true,false,false]);
            
            // Hydrating Project Presentation with Above Attributes
            $pp ->setTitle($title)
                ->setGoal($faker->sentence())
                ->setKeywords($keywords)
                ->setIsPublished($isPublished)
                ->setThumbnailName($thumbnailAddress)
                ->setLogoName($logoAddress)
                ->setCreatedAt($createdAt)
                ->setCreator($creator)
                ->setIsActiveContactMessages($isActiveContactMessages)
                ->setAllowComments($allowComments)
                ->setAdminValidation($isAdminValidated)
            ;
            
            // Project Text Description Creation

            $hasTextDescriptionOdds = [true, true, true, false];

            $hasTextDescription = $hasTextDescriptionOdds [array_rand($hasTextDescriptionOdds)];

            if ($hasTextDescription){
                
                $textDescription = new TextDescription();

                $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';

                $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');

                $textDescription 
                    ->setContent($content)
                    ->setCreatedAt($createdAt)
                    ->setPresentation($pp)
                ;

                $manager->persist($textDescription);
            }

            // Project Owners Creation

            $hasOwnersOdds = [true, true, true, false];

            $hasOwners = $hasOwnersOdds[array_rand($hasOwnersOdds)];
            
            if ($hasOwners){

                $ownersCount = mt_rand(1,4);

                for ($j=1; $j <= $ownersCount; $j++){

                    $owner = new Owner(); // an order is a persorg (person or organisation) with a position

                    $ownerPersorg = new Persorg();

                    $persorgTypes = ['person', 'organisation'];

                    $persorgType = $persorgTypes[array_rand($persorgTypes)];
                
                    $hydratedPersorg = AppFixtures::hydratePersorg($persorgType, $ownerPersorg);

                    $owner->setPersorg($hydratedPersorg);

                    $owner->setPosition($j);

                    $pp->addOwner($owner);

                    $manager->persist($owner);

                }

            }



            // Project Teammates Creation

            $hasTeammatesOdds = [true, true, true, false];

            $hasTeammates = $hasTeammatesOdds [array_rand($hasTeammatesOdds)];
            
            if ($hasTeammates){

                $teammatesCount = mt_rand(1,13);
                
                for ($j=1; $j <= $teammatesCount; $j++){

                    $teammate = new Teammate();

                    $teammatePersorg = new Persorg();
                
                    $hydratedTeammate = AppFixtures::hydratePersorg('person', $teammatePersorg);

                    $teammate->setPersorg($hydratedTeammate);

                    $pp->addTeammate($teammate);

                    $manager->persist($teammate);
                }

            }


            // Creation of Project "External Contributors Structures" (example : Project Donors; Sponsors; Credits; Patrons; etc.) (abbr : "External Contributors Structures" : ecs)


            $hasECSOdds = [true, true, true, false];

            $hasECS = $hasECSOdds [array_rand($hasECSOdds)];
            
            if ($hasECS){

                $eCSCount = mt_rand(1,4);
                
                for ($j=1; $j <=  $eCSCount; $j++){
                
                    $eCS = new ExternalContributorsStructure();

                    $titleChunksChoices = 
                    
                        [
                            "Donors",
                            "Supporters",
                            "Sponsors",
                            "Patrons",
                            "Funders",
                            "Punctual Contributions",
                            "Partners",
                            "Acknowledgments",
                            "Special Thanks",
                            $faker->sentence($nbWords = 3, $variableNbWords = true),
                            $faker->sentence($nbWords = 3, $variableNbWords = true),

                        ];

                        $choosenIndex = array_rand($titleChunksChoices);

                        $title = $titleChunksChoices[$choosenIndex];

                        unset($titleChunksChoices[$choosenIndex]);

                    // sometimes we complete the title with a second title part :

                    if (AppFixtures::chance(0.3)) {

                        $choosenIndex = array_rand($titleChunksChoices);

                        $secondTitlePart = $titleChunksChoices[$choosenIndex];

                        $title = $title.' - '.$secondTitlePart;

                    }

                    // sometimes the structure contains some rich text

                    $structureRichText = NULL;

                    if (AppFixtures::chance(0.3)) {

                        $structureRichText = "";

                        $structureRichText .= '<p>'.join('</p><p>', $faker->paragraphs(2)). '</p>';

                        $listElements=[];

                        for ($b=0; $b < mt_rand(1,7); $b++) { 

                            $listElements[]= $faker->text($maxNbChars = 227);
                        }

                        $structureRichText .= '<ul>'.'<li>'.join('</li><li>', $listElements). '</li>'.'</ul>';

                    }

                    
                    // set structure position (because we can have some different structures)

                    $eCSPosition= $j;

                        /* Creation of sponsors; donators... into the structure */

                        // number of sponsors; donateurs in the structure

                        $sponsorsCount = mt_rand(1,12);

                        // array of sponsors logos images

                        $sponsorsLogos = [];

                        for ($a= 1; $a <= 12; $a++){

                            $sponsorsLogos[] = "img".$a;

                        }

                        // creation of each sponsor profile (sponsor name + logo + description + email + website1 ...)

                        for ($k=1; $k <= $sponsorsCount; $k++){

                            $sponsor = new Persorg();

                            $hydratedSponsor = AppFixtures::hydratePersorg('organisation', $sponsor);

                            // sponsor logo

                            $logoChoiceIndex = array_rand($sponsorsLogos);

                            $logo = $sponsorsLogos[$logoChoiceIndex];

                            unset($sponsorsLogos[$logoChoiceIndex]);

                            // sponsor description

                            /* $hasDescription = [null, null, $faker->paragraph($nbSentences = 3, $variableNbSentences = true)];

                            $description = $hasDescription[array_rand($hasDescription)]; */

                            // sponsor position into the structure

                            $sponsorPosition = $k;

                            $hydratedSponsor
                            
                                ->setPosition($sponsorPosition)
                                //->setDescription($description)
                                ->setImage($logo);
                                //->setName($name)

                            $eCS->addPersorg($sponsor);

                            $manager->persist($sponsor);

                        }


                    $eCS
                            -> setPosition ($eCSPosition)
                            -> setRichTextContent ($structureRichText)
                            -> setTitle($title);

                    $pp->addExternalContributorsStructure($eCS);

                    $manager->persist($eCS);
                }

            }


            // Project Technical Data Creation (= tech data)

            $hasTechDataOdds = [true, true, true, false];

            $hasTechData = $hasTechDataOdds [array_rand($hasTechDataOdds)];
            
            if ($hasTechData){

                $techDataCount = mt_rand(1,13);
                
                for ($j=1; $j <= $techDataCount; $j++){
                
                    $techData = new TechnicalData();

                    $position = $j;

                    $name = $faker->sentence($nbWords = 3, $variableNbWords = true);

                    // we choose at random a numeric value, or a lexical value, or a moked-up spec value

                    $possibleValues = 
                        [
                            $faker->sentence($nbWords = 9, $variableNbWords = true),$faker->numberBetween($min = 4000, $max = 900000),
                            $faker->swiftBicNumber(),
                            $faker->mimeType(),
                        ];

                    $value = $possibleValues [array_rand($possibleValues)];

                    $techData-> setName ($name)
                            -> setValue ($value)
                            -> setPosition ($position);

                    $pp->addTechnicalData($techData);

                    $manager->persist($techData);
                }

            }



            // Project Dates & Events Creation

            $hasEventsOdds = [true, true, true, false];

            $hasEvents = $hasEventsOdds [array_rand($hasEventsOdds)];
            
            if ($hasEvents){

                $eventsCount = mt_rand(1,8);
                
                for ($j=1; $j <= $eventsCount; $j++){
                
                    $event = new Event();

                    $title = $faker->text($maxNbChars = 80);

                    $hasDescription = [null, $faker->paragraph($nbSentences = 3, $variableNbSentences = true)];
                    $description = $hasDescription[array_rand($hasDescription)];
                    
                    $hasBeginDate = 

                        [
                            
                            null, 

                            $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)
                        ];

                    $beginDate = $hasBeginDate[array_rand($hasBeginDate)];
                    

                    $hasBeginTime = [
                        null, 
                        $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)
                    ];

                    $beginTime = $hasBeginTime[array_rand($hasBeginTime)];
                    
                    $hasEndTime = [
                        null, 
                        $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)
                    ];
                    
                    $endTime = $hasEndTime[array_rand($hasEndTime)];
                    
                    $hasBeenUpdatedAt = [
                        null, 
                        $faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)
                    ];
                    
                    $updatedAt = $hasBeenUpdatedAt[array_rand($hasBeenUpdatedAt)];
                    
                    $event
                            -> setUpdatedAt ($updatedAt)
                            -> setBeginDate ($beginDate)
                            -> setBeginTime ($beginTime)
                            -> setEndDate ($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null))
                            -> setEndTime ($endTime)
                            -> setDescription ($description)
                            -> setPosition ($j)
                            -> setTitle($title);

                    $pp->addEvent($event);

                    $manager->persist($event);
                }

            }
            





            // Project Cities (with Postal Codes) Creation

            $numPostalCodes = mt_rand(0,3);

            if ($numPostalCodes > 0){

                for ($j=1; $j<=$numPostalCodes; $j++){
                
                    $city = new GeoDomain();

                    $postalCode = mt_rand(10000,99000);

                    $city -> setPostalCode ($postalCode)
                          -> setCity($faker->words(1)[0]);

                    $pp->addGeoDomain($city);

                    $manager->persist($city);
                }

            }

            // Project Questions and Answers Creation (= QA)

            $numQA = mt_rand(0,7);
            
            if ($numQA > 0){

                for ($j=1; $j<=$numQA; $j++){
                
                    $qa = new QuestionAnswer();

                    $createdAt = $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                    $question = $faker->sentence();
                    $answer =  '<p>'.join('</p><p>',$faker->paragraphs(5)). '</p>';
                    $position = $j;

                    $qa-> setCreatedAt($createdAt)
                            -> setQuestion($question)
                            -> setAnswer($answer)
                            -> setPosition($j)
                            -> setPresentation($pp);

                    $manager->persist($qa);
                }

            }


            // Project News

            $hasNewsOdds = [true, true, true, false];

            $hasNews = $hasNewsOdds [array_rand($hasNewsOdds)];

            if ($hasNews){

            $newsCount = mt_rand(1,12);

                for ($j=1; $j<=$newsCount; $j++){
                
                    $news = new News();

                    $createdAt = $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                    $title = $faker->sentence();
                    $textContent = join($faker->paragraphs(2));

                    $news-> setCreatedAt($createdAt)
                            -> setTitle($title)
                            -> setTextContent($textContent)
                            -> setProject($pp);

                    $manager->persist($news);
                }

            }

            // Project Documents

            $hasDocumentsOdds = [true, false];

            $hasDocuments = $hasDocumentsOdds [array_rand($hasDocumentsOdds)];

            if ($hasDocuments){

            $numDocuments = mt_rand(1,7);

                for ($j=1; $j<=$numDocuments; $j++){
                
                    $document = new Document();

                    $createdAt = $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                    $title = $faker->sentence();
                    $position = $j;

                    $document-> setCreatedAt($createdAt)
                            -> setTitle($title)
                            -> setPosition($j)
                            -> setPresentation($pp);

                    $manager->persist($document);
                }

            }

            // Project Websites Creation

            $numWebsites = mt_rand(0,2);

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

            // Comments Creation

            if ($pp->getAllowComments()==true) {

                $hasComments = array_rand([true,true,true,true,false]);

                if ($hasComments){

                    $numComments = mt_rand(1,20);

                    for ($j=1; $j<=$numComments; $j++){
                    
                        $comment = new Comment();

                        $hasBeenUpdated = array_rand([true,false,false, false]);
                        if ($hasBeenUpdated){
                            $comment-> setUpdatedAt($faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years'));

                        }

                        $hasChilds = array_rand([true,false]);
                        if ($hasChilds){

                            $numChilds = mt_rand(1,7);

                            for ($k=1; $k<=$numChilds; $k++){

                                $child = new Comment();
                                $childCreatedAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                                $childContent=join($faker->paragraphs(2));
                                $childUser = $users[ mt_rand(0, count($users)-1) ];

                                $child
                                -> setCreatedAt($childCreatedAt)
                                -> setContent($childContent)
                                -> setUser($childUser)
                                -> setPresentation($pp);

                                $manager->persist($child);

                                $comment->addChild($child);

                            }
                            

                        }

                        $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                        $content = join($faker->paragraphs(2));
                        $user = $users[ mt_rand(0, count($users)-1) ];

                        $comment
                                -> setCreatedAt($createdAt)
                                -> setUser($user)
                                -> setContent($content)
                                -> setPresentation($pp);

                        $manager->persist($comment);
                    }

                }

            }

            // Contact Messages Creation

            if ($pp->getIsActiveContactMessages()) {

                $hasMessages = array_rand([true,true,true,true,false]);

                if ($hasMessages){

                    $numContactMessages = mt_rand(1,30);

                    for ($j=1; $j<=$numContactMessages; $j++){
                    
                        $contactMessage = new ContactMessage();

                        $hasBeenConsulted = array_rand([true,true,false]);

                        $createdAt = $faker->dateTimeBetween($startDate = 'now', $endDate = '+4 years');
                        $context = $faker->sentence();
                        $title = $faker->sentence();
                        $content = join($faker->paragraphs(1));
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

            }




                        
            // Set some categories to this Project Presentation
            
            $numCat = mt_rand(0,6); // random number of categories for this project

            if ($numCat>0){ //si le projet a des catégories, on en prend au hasard et on lui ajoute

               $catRandKeys = array_rand($categoriesObjects, $numCat);

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

            $hasSlidesOdds = [true, true, true, false];

            $hasSlides = $hasSlidesOdds [array_rand($hasSlidesOdds)];

            if ($hasSlides){
                
                for($j=1;$j<=mt_rand(1,4);$j++){
                    
                    $slide=new Slide();

                    // Slide Media Type Creation

                    $mediaTypes = ['image','image','image','text','text','video'];
                    $mediaType = $mediaTypes[array_rand($mediaTypes)];


                    if($mediaType=="image"){

                        $imagesColors=['ffa500','ff6347','1e90ff','ee82ee','3cb371'];

                        $imageColor=$imagesColors[array_rand($imagesColors)];

                        $imageName = $imageColor.'.gif';

                        $slide->setSlideName($imageName);
                        $slide->setThumbnail($imageName);
                    }

                    if($mediaType=="video"){

                        $videoChoices=['x38_3O2Ips4','nmjH3eN3otM','sthjkqvCEbQ','pWCMOkZ61hA'];

                        $videoChoice=$videoChoices[array_rand($videoChoices)];

                        $videoUrl = 'https://www.youtube.com/embed/'.$videoChoice; 
                        $slide->setUrl($videoUrl);

                        $videoThumbnail = 'https://img.youtube.com/vi/'.$videoChoice.'/mqdefault.jpg';

                        $slide->setThumbnail($videoThumbnail);
                    }

                    if($mediaType=="text"){

                       $textContent = $faker->paragraph($nbSentences = 3, $variableNbSentences = true);

                       $slide->setTextContent($textContent);

                       $slide->setThumbnail(substr($textContent,0,10));
                    }

                    $slide  
                        ->setMediaType($mediaType)
                        ->setCaption($faker->sentence())
                        ->setPP($pp)
                    ;

                    $manager->persist($slide);
                }
            }

            

            // Contact Us Boxes Creation

            for($j=1; $j<=mt_rand(0,4); $j++){
                
                $contact = new Contact();

                // we fill a contact title or not (ex: chief officer)

                $titlePossibilities = [null, $faker->sentence($nbWords = 3, $variableNbWords = true) ];
                $title = $titlePossibilities[array_rand($titlePossibilities)];

                // we fill some email fields for this contact, or not

                $emailsPossibilities = [null, $faker->email(), $faker->email(), $faker->email(), $faker->email(), $faker->email(), $faker->email()];

                $email1 = $emailsPossibilities[array_rand($emailsPossibilities)];
                $email2 = $emailsPossibilities[array_rand($emailsPossibilities)];

                // are displayed emails or not (note : always true at the moment)

                $showEmailPossibilities = [true, true];
                $showEmail =  $showEmailPossibilities[array_rand($showEmailPossibilities)];

                // we fill telephone fields or not

                $telephonesPossibilities = [null, $faker->phoneNumber(), $faker->phoneNumber(), $faker->phoneNumber(), $faker->phoneNumber(), $faker->phoneNumber(), $faker->phoneNumber(), $faker->phoneNumber()];
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
                    ->setPosition($j)
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

            for($j=1;$j<=mt_rand(0,17);$j++){

                $dr = new Need();

                $needTitle = $faker->sentence();

                $needDescription = join($faker->paragraphs(5));

                $createdAt = $faker->dateTimeBetween($startDate = '-4 years', $endDate = 'now');

                // Set a Need Priority
                $priorityPossibilities = ['','','priority'];
                $needPriority = $priorityPossibilities[array_rand($priorityPossibilities)];
                
                // Set a Need Type
                $typePossibilities = ['material','skill','money','advice', 'area','other','task'];
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
            
            $projects[]=$pp;

        }

    
        // report messages creation (exemple : report a bug; an abuse; a comment; ...)

        for ($j=1; $j<=15; $j++) {

            $report = new Report();

            $createdAt = $faker->dateTimeBetween($startDate = '-4 years', $endDate = 'now');

            $author = $users[ mt_rand(0, count($users)-1) ];

            $context = $projects[ mt_rand(0, count($projects)-1) ]->getTitle();

            $messageContent = join($faker->paragraphs(3));

            $report -> setCreatedAt($createdAt)
                    -> setUser($author)
                    -> setContext($context)
                    -> setMessageContent($messageContent);

            $manager->persist($report);

        }


        // Project Groups Creation

        for($i=1; $i<=1; $i++){

            $projectGroup = new PGroup();

            // Group Creator

               $creator=$users[ mt_rand(0, count($users)-1) ];

            // Group Keywords
                $keywordsNumber=mt_rand(0,7);

                if ($keywordsNumber == 0) {
                    $keywords='';
                } else {
                    $keywords=join(', ',$faker->words($keywordsNumber));
                }
                
            // Created At

                $createdAt = $faker->dateTimeBetween($startDate = '-4 years', $endDate = '-3 years');

            // Group Masters Creation (= group admin)

                $groupAdmins= [];

                $groupAdmins [] = $creator; //group creator is always a group admin

                $otherAdminsNumber = mt_rand(1,8);

                for( $j=1; $j<=$otherAdminsNumber; $j++ ){

                    $adminCandidate =  $users[ mt_rand(0, count($users)-1) ];

                    if (! in_array ($adminCandidate, $groupAdmins))
                    {
                        $groupAdmins [] = $adminCandidate;
                        
                        $projectGroup->addMaster($adminCandidate);

                    }

                    else {
                        $j--;
                    }

                }

            // Insertion of projects into the group : different possibilities :  {Included; Candidates; Invited} (Projects) (Creation)

            $projectsPool = $projects; // $projects contains all the projects we previously created, we will pick up projects in this array, in order to populate project groups

            // Group Included Projects Creation

                $groupIncludedProjectsNumber = mt_rand(1,8);

                for( $j=1; $j<=$groupIncludedProjectsNumber; $j++ ){

                   $projectToInclude = $projectsPool[array_rand($projectsPool)];
                    
                   $projectGroup->addIncludedP($projectToInclude);

                    // now this project is in this group, we remove it from our project candidates pool

                   if (($key = array_search( $projectToInclude, $projectsPool)) !== false) {
                        unset($projectsPool[$key]);
                    }

               }

            // Group Candidates Projects Creation
            
            $groupCandidatesProjectsNumber = mt_rand(1,8);

            for( $j=1; $j<=$groupCandidatesProjectsNumber; $j++ ){

               $projectCandidate = $projectsPool[array_rand($projectsPool)];
                
               $projectGroup->addCandidateP($projectCandidate);

                // now this project is in this group, we remove it from our project candidates pool

               if (($key = array_search( $projectCandidate, $projectsPool)) !== false) {
                    unset($projectsPool[$key]);
                }

            }

            // Invited Projects Creation
            
            $invitedProjectsNumber = mt_rand(1,8);

            for( $j=1; $j<=$invitedProjectsNumber; $j++ ){

               $invitedProject = $projectsPool[array_rand($projectsPool)];
                
               $projectGroup->addInvitedP($invitedProject);

                // now this project is in this group, we remove it from our project candidates pool

               if (($key = array_search( $invitedProject, $projectsPool)) !== false) {
                    unset($projectsPool[$key]);
                }

            }


            $projectGroup->setName($faker->sentence())
                        ->setDescription($faker->paragraph($nbSentences = 4, $variableNbSentences = true) )
                        ->setKeywords($keywords)
                        ->setCreatedAt($createdAt)
                        ->setCreator($creator);

            $manager->persist($projectGroup);

        }

        $manager->flush();  

    }

  
}
