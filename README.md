# Task

Please find below our coding exercise.  We prefer to write an exercise in Laravel, but you can use whatever PHP framework you feel comfortable in. 
1. Given the attached CSV data files, write an artisan command/function importData, that will import data from CSVs to DB.
2. Create MVC to display imported data from DB
	1. default view should display currently opened restaurant 
	2. we want to be able to search in restaurants (even closed one)
Optimized solutions are nice, but the correct solutions are more important!
Assumptions:
* If a day of the week is not listed, the restaurant is closed on that day
* All times are local — don’t worry about timezone-awareness
* Don’t hesitate to be creative if you have time for it ;)
If you have any questions, let me know. 


# Solution
Application is based on Symfony 3.4 (stable long-term support version) with Symfony Flex bundle. You can try live demo. Use technologies:
* database: Doctrine
* UI: bootstrap (most basic) and valina JS for ajax search

## Installation
```
composer isntall
```
then you start local server

```
php bin/console server:run
```

and open displayed URL in your browser.
 
## Database
Database credentials and more information how to change/set you find in file **.evn**. Database name is **restaurant** but you can change this.

You can create schema by commad:
```
php bin/console doctrine:migration:migrate
```

## Command - import data from CSV

## Test

```
./vendor/phpunit/phpunit/phpunit
```