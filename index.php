<?php
require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('America/New_York');

// use Monolog\Logger;
// use Monolog\Handler\StreamHandler;

// $log = new Logger('name');
// $log->pushHandler(new StreamHandler('app.log', Logger::WARNING));
// $log->addWarning('Foo!');

$app = new \Slim\Slim( array(
  'view' => new \Slim\Views\Twig()
));

$view = $app->view();
$view->parserOptions = array(
    'debug' => true
);
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension()
);


$app->get('/', function() use($app) {
  $app->render('about.twig');
});

$app->get('/contact', function() use($app)  {
  $app->render('contact.twig');
});

$app->run();

?>
