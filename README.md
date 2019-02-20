# Laravel and Vuejs REST Api, 
### 
 - Social or email registration 
 - Email verification 
 - Login and authentication 
 - Dashboard  

This is a basic SPA with stateless authentication uses JWT. user can login with google, facebook or github, they register with name and email , then they get an email with card code, PIN and a confirmation button, as soon as they confirm we direct them to login site where they can login with the card number and the pin. if authenticated they land on a dashboard.


## Installation

to install execute the following commands get the data on your machine.

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
