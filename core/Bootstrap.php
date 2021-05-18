<?php

namespace Core;

use Buki\Router\Router;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Valitron\Validator;
use Arrilot\DotEnv\DotEnv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Bootstrap
{

    public $router;
    public $view;
    public $validator;
    public $getValidator;

    public function __construct()
    {

        $isDevelopment = isLocalhost() && config('DEVELOPMENT');
        DotEnv::load(dirname(__DIR__) . '/.env.php');

        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());

        if (config('DEVELOPMENT')) {
            $whoops->register();
        }

        Carbon::setLocale(config('LOCALE', 'tr_TR'));

        $capsule = new Capsule;

        $prefix = !$isDevelopment ? "PROD_" : "";
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => config($prefix.'DB_HOST', 'localhost'),
            'database'  => config($prefix.'DB_NAME'),
            'username'  => config($prefix.'DB_USER'),
            'password'  => config($prefix.'DB_PASSWORD'),
            'charset'   => config('DB_CHARSET', 'utf8'),
            'collation' => config('DB_COLLATION', 'utf8_general_ci'),
            'prefix'    => config('DB_PREFIX'),
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->router = new Router([
            'paths' => [
                'controllers' => 'app/controllers',
                'middlewares' => 'app/middlewares'
            ],
            'namespaces' => [
                'controllers' => 'App\Controllers',
                'middlewares' => 'App\Middlewares'
            ]
        ]);
        $this->validator = new Validator($_POST);
        $this->getValidator = new Validator($_GET);
        // Validator::langDir(dirname(__DIR__) . '/public/validator_lang');
        // Validator::lang('tr');
        $this->view = new View($this->validator);
    }

    public function run()
    {
        $this->router->run();
    }

}