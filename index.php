<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Google\Client as Google_Client;

require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

// .env - Load environment variables from the project root directory
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();

$app->setBasePath($_ENV['BASE_DIR']);
//var_dump($app->getBasePath());

// Get the default error handler and inject a custom logger if needed
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json'); // Example: force JSON responses for errors

// Works
$app->get("/", function (Request $request, Response $response, $args) {
    $response->getBody()->write("Myello?");
    return $response;
});

// works - JSON output of rows and columns 
$app->get("/sheet-data", function (Request $request, Response $response, $args) {
    // Your Google API client logic goes here
    $client = new Google_Client();
    $client->setApplicationName('googleSheetsAPI-SA');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig('credentials.json');
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = '1iT0zucmS9y1cMBHIpujU2IYb_ehfkK3Eu1t1QdnNtfg';
    $range = 'Sheet1!A1:D1000'; // Example A1 notation range

    $result = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $result->getValues();

    $response->getBody()->write(json_encode($values));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get("/myello", function ($request, $response) {
    $renderer = new PhpRenderer(__DIR__ . '/templates');
    // var_dump($renderer);
    
    $viewData = [
        'name' => 'Damien',
    ];
    
    return $renderer->render($response, 'songlist.phtml', $viewData);
})->setName('profile');

$app->get("/songlist", function ($request, $response) {
    $renderer = new PhpRenderer(__DIR__ . '/templates');
    // var_dump($renderer);
    //var_dump($_ENV);

    // Your Google API client logic goes here
    $client = new Google_Client();
    $client->setApplicationName('googleSheetsAPI-SA');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig([
        'type' => 'service_account',
        'project_id' => $_ENV['GOOGLE_PROJECT_ID'],
        'private_key_id' => $_ENV['GOOGLE_PRIVATE_KEY_ID'],
        'private_key' => str_replace('\\n', "\n", $_ENV['GOOGLE_PRIVATE_KEY']),
        'client_email' => $_ENV['GOOGLE_CLIENT_EMAIL'],
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
        'auth_uri' => $_ENV['GOOGLE_AUTH_URI'],
        'token_uri' => $_ENV['GOOGLE_TOKEN_URI'],
        'auth_provider_x509_cert_url' => $_ENV['GOOGLE_AUTH_PROVIDER_X509_CERT_URL'],
        'client_x509_cert_url' => $_ENV['GOOGLE_CLIENT_X509_CERT_URL'],
    ]);
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = '1iT0zucmS9y1cMBHIpujU2IYb_ehfkK3Eu1t1QdnNtfg';
    $range = 'Sheet1!A1:D1000'; // Example A1 notation range

    $result = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $result->getValues();

    $viewData = [
        'list' => $values,
    ];

    return $renderer->render($response, 'songlist.phtml', $viewData);

})->setName('profile');

$app->run();