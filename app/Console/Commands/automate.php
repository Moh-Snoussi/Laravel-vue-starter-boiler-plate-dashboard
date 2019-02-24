<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class automate extends Command
{
    private $firstwrite = 0;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to automate tasks we used to do by hand';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function setEnvironmentValue(array $values)
    {

        $envFile = $this->firstwrite ? app()->environmentFilePath() : app()->environmentFilePath() . '.example';
        $destination = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {

                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        $this->firstwrite++;
        if (!file_put_contents($destination, $str)) return false;
        return true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
        //
        $this->info('this is an automation script.');
        $this->info('to install node, setup database, install passport, run migration, and lunch the server.');
        $this->info('');
        $this->info('you can always change those settings from the .env file on the root folder');
        $this->info('');
        $this->info('in case you skip:');
        $this->info('you need to follow those instructions: https://github.com/Moh-Snoussi/Automated_Teller_Machine for manual instalation.');
        $this->info('');
        if ($this->confirm('press enter to continue auto assistance, or type no to skip (default):', 'yes')) {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                system('cls');
            } else {
                system('clear');
            }


            $this->info('<<<<<<<<<<<<<<<<<<<------NPM----->>>>>>>>>>>>>>>>>>');
            $this->info('if you have node modules already installed for this project then you can skip this step.');
            if ($this->confirm('press enter to install node modules, or type no to skip (default):', 'yes')) {
                $this->info('npm install');

                $this->info('please wait node modules installations');
                passthru("npm install");

                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    system('cls');
                } else {
                    system('clear');
                }
            }
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                system('cls');
            } else {
                system('clear');
            }

            $this->info('if you have database,email setting already configured in .env file then you can skip this step.');
            $this->info('this step will create a new .env file in the root folder of the project.');
            $this->info('if there is already an .env file in the root folder it will be overwritten.');
            if ($this->confirm('press enter to setup a new .env file with database,email,socialite settings, or type no to skip (default):', 'yes')) {
                $this->info('<<<<<<<<<<<<<<<<<<<------DATABASE----->>>>>>>>>>>>>>>>>>');
                $this->info('database configuration');
                $this->info('you will be asked to enter database host, username, password...');
                if ($this->confirm('do you want to set up your database now? (default):', 'yes')) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }

                    $this->info('setting up database host');
                    $this->info('type new host or press enter to keep default');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $db = $this->ask('Database host (default):', '127.0.0.1');



                    $this->setEnvironmentValue(['DB_HOST' => $db]);
                    $this->info('database host is set to ' . $db);



                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                    $this->info('setting database port');

                    $this->info('you can always change those settings from the .env file on the root folder ');
                    $this->info('type new port or press enter to keep default');

                    $db = $this->ask('Database port (default):', '3306');

                    $this->setEnvironmentValue(['DB_PORT' => $db]);
                    $this->info('database port is set to ' . $db);


                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }

                    $this->info('setting database name');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('type new name or press enter to keep default');

                    $db = $this->ask('Database name (default):', 'ATMDB');

                    $this->setEnvironmentValue(['DB_DATABASE' => $db]);
                    $this->info('database name is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }


                    $this->info('setting database username');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('Username');

                    $db = $this->ask('Please enter your database username');

                    $this->setEnvironmentValue(['DB_USERNAME' => $db]);
                    $this->info('database username is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }

                    $this->info('setting database password');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('Password');
                    $db = $this->ask('Database password');

                    $this->setEnvironmentValue(['DB_PASSWORD' => $db]);
                    $this->info('database password is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                }
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    system('cls');
                } else {
                    system('clear');
                }
                $this->info('<<<<<<<<<<<<<<<<<<<------EMAIL----->>>>>>>>>>>>>>>>>>');
                $this->info('email configuration');
                $this->info('you will be asked to enter email host, username, password...');
                if ($this->confirm('do you want to set email configuration now', 'yes')) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }

                    $this->info('setting email driver');
                    $this->info('type new mail driver or press enter to keep default');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $db = $this->ask('email driver (default):', 'smtp');



                    $this->setEnvironmentValue(['MAIL_DRIVER' => $db]);
                    $this->info('email driver is set to ' . $db);



                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                    $this->info('setting email host');

                    $this->info('you can always change those settings from the .env file on the root folder ');
                    $this->info('type new host or press enter to keep default');

                    $db = $this->ask('email host (default):', 'smtp.mailtrap.io');

                    $this->setEnvironmentValue(['MAIL_HOST' => $db]);
                    $this->info('email host is set to ' . $db);


                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                    $this->info('setting email port');

                    $this->info('you can always change those settings from the .env file on the root folder ');
                    $this->info('type new port or press enter to keep default');

                    $db = $this->ask('email port (default):', '2525');

                    $this->setEnvironmentValue(['MAIL_PORT' => $db]);
                    $this->info('email port is set to ' . $db);


                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }

                    $this->info('setting email username');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('type new name or press enter to keep default');

                    $db = $this->ask('email username');

                    $this->setEnvironmentValue(['MAIL_USERNAME' => $db]);
                    $this->info('email username is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }


                    $this->info('setting database email from');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('email from');

                    $db = $this->ask('Please enter the adress that we send the email');

                    $this->setEnvironmentValue(['MAIL_FROM_ADDRESS' => $db]);
                    $this->info('database email adress is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }

                    $this->info('setting email password');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('Password');
                    $db = $this->ask('email password');

                    $this->setEnvironmentValue(['MAIL_FROM_ADDRESS' => $db]);
                    $this->info('email password is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                    $this->info('setting email ecryption');
                    $this->info('you can always change those settings from the .env file on the root folder');
                    $this->info('ecryption');
                    $db = $this->ask('email encryption (default):', 'null');

                    $this->setEnvironmentValue(['MAIL_ENCRYPTION' => $db]);
                    $this->info('email ecryption is set to ' . $db);

                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                }


                if ($this->confirm('do you want to set up sign in with social Companies', 'no')) {
                    $this->info('<<<<<<<<<<<<<<<<<<<------SOCIALITE----->>>>>>>>>>>>>>>>>>');
                    $this->info('<<<<<<<<<<<<<<<<<<<------GOOGLE----->>>>>>>>>>>>>>>>>>');
                    $this->info('set sign in with google');
                    $this->info('before you confirm you need to create credentials and get your client id and client secret from https://console.developers.google.com/apis  set up authorized JavaScript origins (http://localhost:3001) and Authorized redirect URIs (http://localhost:8000/login/provider/callback/google) be aware that the your local host or port may not be the same');
                    $this->info('---------------------------------------------------------');
                    if ($this->confirm('do you confirm to set sign in with google', 'no')) {
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }

                        $this->info('setting google client id');
                        $this->info('type your google client id');
                        $this->info('you can always change those settings from the .env file on the root folder');
                        $db = $this->ask('google client id');



                        $this->setEnvironmentValue(['GOOGLE_CLIENT_ID' => $db]);
                        $this->info('google lient id is set to ' . $db);



                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }
                    }


                    $this->info('<<<<<<<<<<<<<<<<<<<------GITHUB----->>>>>>>>>>>>>>>>>>');
                    $this->info('set sign in with github');
                    $this->info('before you confirm you need to create credentials and get your client id and client secret from github api, set up authorized JavaScript origins (http://localhost:3001) and Authorized redirect URIs (http://localhost:8000/login/provider/callback/google) be aware that the your local host or port may not be the same');
                    $this->info('---------------------------------------------------------');
                    if ($this->confirm('do you confirm to set sign in with github', 'no')) {
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }

                        $this->info('setting github client id');
                        $this->info('type your github client id');
                        $this->info('you can always change those settings from the .env file on the root folder');
                        $db = $this->ask('github client id');



                        $this->setEnvironmentValue(['GITHUB_CLIENT_ID' => $db]);
                        $this->info('github lient id is set to ' . $db);



                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }
                    }

                    $this->info('<<<<<<<<<<<<<<<<<<<------FACEBOOK----->>>>>>>>>>>>>>>>>>');
                    $this->info('set sign in with facebook');
                    $this->info('before you confirm you need to create credentials and get your client id and client secret from facebook api, set up authorized JavaScript origins (http://localhost:3001) and Authorized redirect URIs (http://localhost:8000/login/provider/callback/google) be aware that the your local host or port may not be the same');
                    $this->info('---------------------------------------------------------');
                    if ($this->confirm('do you confirm to set sign in with facebook', 'no')) {
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }

                        $this->info('setting facebook client id');
                        $this->info('type your facebook client id');
                        $this->info('you can always change those settings from the .env file on the root folder');
                        $db = $this->ask('facebook client id');



                        $this->setEnvironmentValue(['FACEBOOK_CLIENT_ID' => $db]);
                        $this->info('facebook lient id is set to ' . $db);



                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }
                    }

                    $this->info('<<<<<<<<<<<<<<<<<<<------TWITTER----->>>>>>>>>>>>>>>>>>');
                    $this->info('set sign in with twitter');
                    $this->info('before you confirm you need to create credentials and get your client id and client secret from twitter api, set up authorized JavaScript origins (http://localhost:3001) and Authorized redirect URIs (http://localhost:8000/login/provider/callback/google) be aware that the your local host or port may not be the same');
                    $this->info('---------------------------------------------------------');
                    if ($this->confirm('do you confirm to set sign in with twitter', 'no')) {
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }

                        $this->info('setting twitter client id');
                        $this->info('type your twitter client id');
                        $this->info('you can always change those settings from the .env file on the root folder');
                        $db = $this->ask('twitter client id');



                        $this->setEnvironmentValue(['TWITTER_CLIENT_ID' => $db]);
                        $this->info('twitter lient id is set to ' . $db);



                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            system('cls');
                        } else {
                            system('clear');
                        }
                    }
                }
            }
            $this->info('executing composer require laravel/passport');
            $this->info('please wait');
            passthru('composer require laravel/passport');
            $this->info('running migration');
            $this->info('please wait');
            passthru('php artisan migrate');

            $this->info('registering passport');
            $this->info('please wait');
            passthru('php artisan passport:install');
            $this->info('clearing cach');
            $this->info('please wait');
            passthru('php artisan cach:clear');


            $this->info('lunching the server');
            $this->info('please wait');
            passthru('php artisan serve');
        }
    }
}
