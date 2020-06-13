# Meta-Project
A website platform dedicated to human projects presentation. In particular, you can present your project needs (ex: materials, skills, advices), and people can contact you. Coded with Symfony PHP Framework



# Installation Procedure 
	
#### 1- Download or clone github repository
git clone https://github.com/LoloForThePOP/meta-project.git

#### 2- Move to the right folder
cd meta-project

#### 3- Install Dependancies
composer install

#### 4.a- Configure your database access : in the .env file :
Example for mysql : go to this line : DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name \
Then, for example, replace like this (adapt to your own) : DATABASE_URL=mysql://root:@127.0.0.1:3306/projo

#### 4.b- Create Database
php bin/console doctrine:database:create

#### 5- Execute migrations
php bin/console doctrine:migrations:migrate

#### 6- Execute Fixtures
php bin/console doctrine:fixtures:load --no-interaction

#### 7- Launch Localhost
symfony server:start
