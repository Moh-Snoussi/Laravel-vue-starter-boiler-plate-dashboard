# Laravel - Vuejs authentication and dashboard REST Api

<a href="https://ci.appveyor.com/api/projects/status/{{status_id}}" title="Building" rel="nofollow"><img src="https://ci.appveyor.com/api/projects/status/{{status_id}}g" alt="Building"></a>

<a href="https://codeclimate.com/github/codeclimate/codeclimate/maintainability"><img src="https://api.codeclimate.com/v1/badges/a99a88d28ad37a79dbf6/maintainability" /></a>
<a href="https://codeclimate.com/github/codeclimate/codeclimate/test_coverage"><img src="https://api.codeclimate.com/v1/badges/a99a88d28ad37a79dbf6/test_coverage" /></a>

<img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/php.png" title="php"> <img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/ecmascript.png" title="ECMASCRIPT"> <img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/node.png" title="nodejs"> <img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/composer.png" title="composer"> <img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/larvel.png" title="laravel"> <img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/vue.png" title="vuejs">



### Screenshots

<p align="center">

<img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/public/images/dashboard.png" width="350" title="hover text"> 
  <img src="https://raw.githubusercontent.com/Moh-Snoussi/Automated_Teller_Machine/one_command_and_auto_assistance/public/images/logscreen.png" width="350" alt="accessibility text">
  </p>


 - Social or email registration 
 - Email verification 
 - Login and authentication 
 - Dashboard  

This is a basic SPA with stateless authentication uses JWT. user can login with google, facebook or github, they register with name and email , then they get an email with card code, PIN and a confirmation button, as soon as they confirm we direct them to login site where they can login with the card number and the pin. if authenticated they land on a dashboard.
## Requirements

- Composer
- Node
- Database credentials and server configuration
- Email credentials and server configuration (you can get for free from https://mailtrap.io)

# Instalation
### One Command Installation


1. Open terminal and execute the following command:

   `git clone -b one_command_and_auto_assistance https://github.com/Moh-Snoussi/Laravel-vue-starter-boiler-plate-dashboard && cd Laravel-vue-starter-boiler-plate-dashboard && composer install && php artisan automate`

2. Follow the instructions. 

3. To edit vue files and see the change on browser as soon as you save then open another terminal in the project directory and run:

    ```npm run watch -- --watch-poll```

## Or
### Manual Installation

to install open terminal and execute the following commands:

1. Clone or download this repository

   ```git clone https://github.com/Moh-Snoussi/Automated_Teller_Machine```

2. Navigate to the repository with your terminal 

   ```cd Automated_Teller_Machine```
3. Install php dependencies

   ```composer install```
4. Install node dependencies

   ```npm install```
5. Rename .env.example from the root diretory to .env

   .Before: 
   >.env.example

   .After
   >.env
6. Modify the new .env

   .Enter your database credentials 
   
   .Enter your email credentials and server settings (you can get for free from https://mailtrap.io)

   .Optional set up a client id and client secret for any provider you choose

   .Optional set the call and the callback url same as in .env

7. Install laravel passport, back to your terminal and run:

   ```composer require laravel/passport```

8. Migrate to the data base

   ```php artisan migrate``` 
9. Generate passport keys

   ```php artisan passport:install```
10. Run server

    ```php artisan serve```
11. To edit the vue files and see the change on browser as soon as you save then in another terminal on the project directory run:

    ```npm run watch -- --watch-poll```
