<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Object\Callback;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
    $app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	});

    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/callback', Callback::class . ':process');

        $group->group('/measures', function (RouteCollectorProxy $mes) {
            $mes->get('', \App\Application\Object\Measure::class . ':get');
            $mes->get('/{from}/{to}', \App\Application\Object\Measure::class . ':getFromTo');
            $mes->put('/{id:[0-9]+}', \App\Application\Object\Measure::class . ':update');
            $mes->delete('/{id:[0-9]+}', \App\Application\Object\Measure::class . ":delete");
            $mes->options('/{id:[0-9]+}', function ($req, $response, $args) {
            	        $response->getBody()->write('Vroum vroum');
        				return $response->withHeader('Content-Type', 'application/json');
            });
        });
    });
};
