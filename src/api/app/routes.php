<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Object\Callback;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/callback', Callback::class . ':process');

        $group->group('/measures', function (RouteCollectorProxy $mes) {
            $mes->get('', \App\Application\Object\Measure::class . ':get');
            $mes->get('/{from}/{to}', \App\Application\Object\Measure::class . ':getFromTo');
            $mes->put('/{id}', \App\Application\Object\Measure::class . ':update');
            $mes->delete('/{id}', \App\Application\Object\Measure::class . ':delete');
        });
    });
};
