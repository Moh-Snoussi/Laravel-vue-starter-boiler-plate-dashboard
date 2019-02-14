This is a project in progress we want to build the a strong and beautiful application.

A basic SPA with stateless authentication uses JWT. user can also login with google, facebook or github,

Users can register with name and email , then they get an email with card code, PIN and a confirmation button, as soon as they confirm we direct them to login site where they can login with the card number and the pin. if authenticated they land on a dashboard.

the class associated with this work is on app/Notification/SignupActivate.php that get called by app/http/controllers/AuthController.php

Implemented as well a validation against brute force attack where we block login attempts if there was 8 failed login attempt in three minuets.

the front end is with vuejs its entry point is on resources/views/welcome.blade.php, its logic and components lives on resources/views/js/App.js.

to install execute the following commands get the data on your mahine #git clone https://github.com/Moh-Snoussi/Automated_Teller_Machine

#cd Automated_Teller_Machine

install php dependencies #composer install

install java-script dependencies (node) #npm install

rename env.example on the root directory to .env

edit the new .env and enter your : data base information email client informtion you can get for free from https://mailtrap.io (optional)for the login with google,facebook or github we need get auoth credential from providers platform.

at this point you should have your database ready

setup Laravel

then run the following commands:

#php artisan migrate:fresh

#php artisan passport:instal
finaly
#php artisan serve
