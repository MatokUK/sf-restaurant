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
composer install
```
then you start local server

```
php bin/console server:run
```

and open displayed URL in your browser.
 
## Database
Database credentials and more information how to change/set you find in file **.evn**. Database name is **restaurant** but you can change this.

You can create schema by command:
```
php bin/console doctrine:migration:migrate
```

Other way is to execute this commands:
```sql
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190310085956',	'2019-03-10 09:00:30');

CREATE TABLE `restaurant` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuisine` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `restaurant_opening_interval` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(10) unsigned NOT NULL,
  `day_in_week` smallint(6) NOT NULL,
  `open_at` time NOT NULL,
  `close_at` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BA887A07B1E7706E` (`restaurant_id`),
  CONSTRAINT `FK_BA887A07B1E7706E` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
## Command - import data from CSV

Run import command:
```
php bin/console slido:read:csv
```
Paths to CSV files are hardcoded. Alternatively you can use -t argument to truncate all data( which actually do DELETE).

```
php bin/console slido:read:csv -t
```
## Test
TDD is my way of coding - I created several tests.

```
./vendor/phpunit/phpunit/phpunit
```

## Final words
 * I don't use paginator in listing (only limit to 100 records), but in real application I definitely paginate results
 * I have got obstacles with times like **5:30 pm - 2 am**, because 2 am overlaps to next day, so I split such intervals in two: "to midnight" and "after midnight"