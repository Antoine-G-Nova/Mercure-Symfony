# Install
1) `composer install`
2) create "mercure" database and complete .env file with your credentials
3) `php bin/console doctrine:database:create`
4) `php bin/console make:mi`
5) `php bin/console doctrine:mi:mi`
6) `php bin/console doctrine:fix:load`
7) Go to ./Mercure folder and execute the following cmd : `./mercure --jwt-key='!ChangeMe!' --addr=':3000' --debug --cors-allowed-origins='http://localhost' --publish-allowed-origins='http://localhost:3000'`
8) Go to : http://localhost/Mercure-Symfony/public/login
9) Login with username : K.Dunglas || Antoine || Sarah (No password needed)
10) Enjoy!  😉