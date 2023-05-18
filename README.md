# COMMISSION CALCULATOR 

Commission Calculator is a light weight application written in pure PHP, that accepts csv transaction file as input and based on commission rule from the config file, calculates commission for every transaction in the csv

# Specialities:
 - PSR-4 and PSR-12 complaint
 - Maintains SOLID principle
 - Light weight
 - Composer dependency management system
 - Includes Unit Tests

# Prerequisites:
- Clone this project to your local machine/download the zip file and unzip
- PHP 8.1 or above needs to be installed 
- composer needs to be installed 
- Download composer [Composer Website](https://getcomposer.org) from here if not installed.


# To Run the Project
- go to the project directory root
- execute the following command first
```sh
composer install 
```
- after the command is executed, please execute the following command in your terminal
```sh
php src/app.php input.csv
```

# To Run unit tests
- go to the project directory root
- run 
```sh 
composer test
```
or
```sh 
composer phpunit
```
or (for more detailed info run below command)
```sh
vendor/phpunit/phpunit/phpunit --testdox tests
```

# To Change Configuration
- open `project_directory/src/Configs/AppConfig.php`
- to change Free Withdraw Limit for private clients, change the value of `withdraw_free_limit`
- to change deposit fee, update the value of `deposit_fee`
- to change withdrawal fee for Business Client, update the value of `withdraw_fee_business`
- to change withdrawal fee for Private Client, update the value of `withdraw_fee_private`
- to change/update currency exchange API, update the value of `currency_exchange_api`

# To Change Test Set
- open `project_directory/input.csv`
- change/add new transaction records
- Please make sure, test transaction records are sorted by date if changed or appended new records. 

# To Autoload
- go to the project directory root
- execute the following command to generate classmap for optimal performance
```sh
composer dump-autoload -o
```
- Project is tested on a machine with PHP version  8.2.1, however it should run fine on a any machine with php version 8.1 and above.
- Example csv is expected to be sorted by date 
- Broken records in csv are not handled