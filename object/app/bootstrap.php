<?php
    /**
     * Created by PhpStorm.
     * User: Charles.Martin
     * Date: 8/11/2015
     * Time: 10:21 AM
     */

    (new \Phalcon\Debug())->listen();

    $pLoader = new \Phalcon\Loader();
    $pLoader->registerDirs(
        [
            __DIR__ . DIRECTORY_SEPARATOR . 'controllers',
            __DIR__ . DIRECTORY_SEPARATOR . 'models',
            __DIR__ . DIRECTORY_SEPARATOR . 'views',
        ]
    )->register();

    $diService = new \Phalcon\Di\FactoryDefault();
    $diService->set(
        'view',
        function() {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(__DIR__ . DIRECTORY_SEPARATOR . 'templates');
            return $view;
        }
    );

    $diService->set(
        'dispatcher',
        function() {
            $dispatcher = new \Phalcon\Mvc\Dispatcher();

            $dispatcher->setDefaultController('Address');

            return $dispatcher;
        }
    );

    $diService->set(
        'eventsManager',
        new \Phalcon\Events\Manager()
    );

    $diService->set(
        'db',
        function() use ($diService) {
            $config = [
                'host'     => 'localhost',
                'username' => 'root',
                'password' => '123',
                'dbname'   => 'dbname'
            ];

            $db = new \Phalcon\Db\Adapter\Pdo\Mysql($config);
            $db->setEventsManager($diService->getEventsManager());

            return $db;
        }
    );

    $diService->setShared(
        'session',
        function() {
            $session = new \Phalcon\Session\Adapter\Files();
            $session->start();
            return $session;
        }
    );

    $diService->set('flash', function (){
        $flash = new \Phalcon\Flash\Session([

            //tie in with twitter bootstrap classes
            'error'     => 'alert alert-danger',
            'success'   => 'alert alert-success',
            'notice'    => 'alert alert-info',
            'warning'   => 'alert alert-warning'
        ]);
        return $flash;
    });

    \Phalcon\Di::setDefault($diService);

    $app = new \Phalcon\Mvc\Application($diService);
    echo $app->handle()->getContent();