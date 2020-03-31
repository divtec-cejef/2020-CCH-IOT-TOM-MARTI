<?php
declare(strict_types=1);

use App\Application\Object\Callback;
use App\Application\Object\Device;
use App\Application\Object\Measure;
use App\Application\Object\Room;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->post('/callback', Callback::class . ':process');

    $app->group('/devices', function (RouteCollectorProxy $group) {
        $group->get('', Device::class . ':getAll');
        $group->get('/{id}', Device::class . ':get');
        $group->get('/{id}/measures', Device::class . ':getMeasures');
        $group->get('/{id}/measures/{date}', Device::class . ':getMeasuresAtDate');
        $group->get('/{id}/measures/{from}/{to}', Device::class . ':getMeasuresFromTo');
    });

    $app->group('/rooms', function (RouteCollectorProxy $group) {
        $group->get('', Room::class . ':getAll');
        $group->get('/{id}', Room::class . ':get');
        $group->get('/{id}/measures', Room::class . ':getMeasures');
        $group->get('/{id}/measures/{date}', Room::class . ':getMeasuresAtDate');
        $group->get('/{id}/measures/{from}/{to}', Room::class . ':getMeasuresFromTo');
    });

    $app->group('/measures', function (RouteCollectorProxy $group) {
        $group->get('', Measure::class . ':getAll');
        $group->get('/{date}', Measure::class . ':getDate');
        $group->get('/{from}/{to}', Measure::class . ':getFromTo');
    });
};
