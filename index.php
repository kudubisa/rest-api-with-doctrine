<?php
require_once("vendor/autoload.php");
require_once("Dbal.php");

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

//This is Slim Section
$app = new \Slim\Slim(
    array(
        "debug" => true,
        "mode" => "development"
    )
);

$dbal = new \MyDoctrine\Dbal;
$conn = $dbal->getConnection();


$client = new Raven_Client('https://8b4c52975e52402ca5e9e811c2e62c20:99e25f6920f24704949c974f473eea1a@sentry.io/244051');
$error_handler = new Raven_ErrorHandler($client);
$error_handler->registerExceptionHandler();
$error_handler->registerErrorHandler();
$error_handler->registerShutdownFunction();
$client->install();


//SWAGGER SECTION


// $swagger = \Swagger\scan('/');
// header('Content-Type: application/json');
// echo $swagger;


$app->get("/", function () use ($conn){

    $category = new \Jogjacamp\Belajar\Category;
    $adapter = $category->retrieve_all();

    //common thing
    $pagerfanta = new Pagerfanta($adapter);

    //arguments
    $maxPerPage = 5;
    $currentPage = 1;

    $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default
    $maxPerPage = $pagerfanta->getMaxPerPage();

    $pagerfanta->setCurrentPage($currentPage); // 1 by default
    $currentPage = $pagerfanta->getCurrentPage();

    $nbResults = $pagerfanta->getNbResults();
    $currentPageResults = $pagerfanta->getCurrentPageResults();

    $num_page = $pagerfanta->getNbPages();

    // $pagerfanta->haveToPaginate(); // whether the number of results is higher than the max per page

    // $pagerfanta->hasPreviousPage();
    // $pagerfanta->getPreviousPage();
    // $pagerfanta->hasNextPage();
    // $pagerfanta->getNextPage();

    // $pagerfanta->getCurrentPageOffsetStart();
    // $pagerfanta->getCurrentPageOffsetEnd();

    echo json_encode(array(
        'name'              => 'Park Han Byul',
        'num_page'          => $num_page,
        'current_page'      => $currentPageResults,
        'has_next_page'     => $pagerfanta->hasNextPage(),
        'next_page'         => $pagerfanta->getCurrentPageResults($pagerfanta->setCurrentPage(2)),
        'previous_page'     => $pagerfanta->getPreviousPage(),
        'page_offset_start' => $pagerfanta->getCurrentPageOffsetStart(),
        'page_offset_end'   => $pagerfanta->getCurrentPageOffsetEnd()
    ));


    // $koneksi = $category->getConnection();

    // $sm = $koneksi->getSchemaManager();

    // $dbList = $sm->listDatabases();
    // // $table = $sm->listTableDetails('');
    // //$sequences = $sm->listSequences('rest_api');

    // // for($sequences as $sequence){
    // //  echo $sequence->getName()."\n";
    // // }

    // echo json_encode($dbList);


});




$app->get('/swagger.json', function () use ($app) {
    $swagger = \Swagger\scan('./routes/');
    // header('Content-Type: application/json');

    $response = $app->response;
    $response->setStatus("200");
    $response->headers->set("Content-Type","application/json");
    $response->write(json_encode($swagger));
    // echo $swagger;
});


require(__DIR__."/routes/category.php");
require(__DIR__."/routes/app.php");







$app->run();










































