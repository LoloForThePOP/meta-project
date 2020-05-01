# meta-project
A website platform dedicated to human projects presentation. In particular, you can present your project needs (ex: materials, skills, advices), and people can contact you. Coded with Symfony PHP Framework



# Installation Procedure 
	
1- Download or clone github repository
git clone https://github.com/LoloForThePOP/meta-project.git

2- Move to the right folder
cd meta-project

3- Install Dependancies
composer install

4- Create Database
php bin/console doctrine:database:create

5- Execute migrations
php bin/console doctrine:migrations:migrate

6- Execute Fixtures
php bin/console doctrine:fixtures:load --no-interaction

7- Launch Localhost
symfony server:start
