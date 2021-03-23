# Meta-Project
A website platform dedicated to human projects presentation. In particular, you can present your project needs (ex: materials, skills, advices), and people can contact you. Coded with Symfony PHP Framework

# Installation Procedure 

#### Remark: I use Visual Studio Code, along with its included terminal Windows PowerShell

#### 0-a- Prerequisites: make sure these programs are installed:

* Install **Git** (Git is a version control system for tracking changes in computer files) (https://git-scm.com/downloads)

* Install **PHP (7.1 minimum)** along with **MySQL**  (to do so, if you use windows os, you can install WAMP Server for example)

* Install **Composer** (Composer is an application-level package manager for the PHP programming language) (https://getcomposer.org/download/)


#### 0-b- Test your configuration: to do so, type the following commands in a terminal:

* type the command **git** in a terminal, and make sure there is no error message

* type the command **php -v**, and make sure you have et least version 7.1 

* type the command **composer -V** , and make sure there is no error message

#### Remark: each time you develop, make sure MySQL is launched (to do so, you simply have to run wampserver)
	
#### 1- Download or clone github repository: in a terminal:
git clone https://github.com/LoloForThePOP/meta-project.git

#### 2- Move to the right folder
cd meta-project

#### 3- Enable a .env file
rename the file ".env.example" to ".env" (so you only got to remove ".example" in the file name)

#### 4- Install Dependancies
composer install

#### 5- Configure your database access: in the .env file:
Example for mysql: go to this line: DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name

Then, for example, replace it like this (adapt to your own): DATABASE_URL=mysql://root:@127.0.0.1:3306/projectofprojects

#### 6- Create Database
php bin/console doctrine:database:create

#### 7-b Execute migrations
php bin/console doctrine:migrations:migrate

#### 8- Execute Fixtures
php bin/console doctrine:fixtures:load --no-interaction

#### 9- Start your Local Web Server

You can **install "Symfony Local Web Server"** (https://symfony.com/download)

Then, in a terminal, type : symfony server:start

#### Remarks: if you develop with Visual Studio Code, some extensions might help you, in particular:

* PHP Import and expand your class: you can install PHP Namespace Resolver.

* PHP Document your code (example: Completion snippet after /** above a class, function, class property): you can install PHP DocBlocker.

* Twig files management (syntax highlighting; snippets; Emmet): you can install Extension Twig Language 2.

remark: to enable Emmet with twig files: go to File -> Preferences -> Setings -> Text Editor -> Files -> Associations -> edit settings.json -> (add following lines):

```
"emmet.includeLanguages": {
        "twig": "html",
    },
```


