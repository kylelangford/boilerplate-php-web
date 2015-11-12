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
})->name('home');


$app->get('/contact', function() use($app)  {
  $app->render('contact.twig');
})->name('contact');

$app->post('/contact', function() use($app)  {
  $name = $app->request->post('name');
  $email = $app->request->post('email');
  $msg = $app->request->post('msg');

  if(!empty($name) && !empty($email)  && !empty($msg)) {
    $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
    $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    $cleanMsg = filter_var($msg, FILTER_SANITIZE_STRING);

  } else {
    // show error
    $app->redirect('/contact');
  }

  $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
  $mailer = \Swift_Mailer::newInstance($transport);

  $message = \Swift_Message::newInstance();
  $message->setSubject('Email from our website');
  $message->setForm(array(
    $cleanEmail => $cleanName
  ));
  $message->setTo(array('treehouse@localhost'));
  $message->setBody($cleanMsg);

  $result = $mailer->send($message);

  if($result > 0) {
    // Send msg to user
    $app->redirect('/');
  } else {
    // Send msg to user
    // Log error
    $app->redirect('/contact');
  }
});

$app->run();

?>
