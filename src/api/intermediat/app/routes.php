<?php
declare(strict_types=1);

use App\Application\Object\Callback;
use App\Application\Object\Device;
use App\Application\Object\Measure;
use App\Application\Object\Room;
use Slim\App;

return function (App $app) {
    $app->post('/callback', Callback::class . ':process');

    $app->get('/devices', Device::class . ':getAll');
    $app->get('/devices/{id}', Device::class . ':get');
    $app->get('/devices/{id}/measures', Device::class . ':getMeasures');
    $app->get('/devices/{id}/measures/{date}', Device::class . ':getMeasuresAtDate');
    $app->get('/devices/{id}/measures/{from}/{to}', Device::class . ':getMeasuresFromTo');

    $app->get('/rooms', Room::class . ':getAll');
    $app->get('/rooms/{id}', Room::class . ':get');
    $app->get('/rooms/{id}/measures', Room::class . ':getMeasures');
    $app->get('/rooms/{id}/measures/{date}', Room::class . ':getMeasuresAtDate');
    $app->get('/rooms/{id}/measures/{from}/{to}', Room::class . ':getMeasuresFromTo');

    $app->get('/measures', Measure::class . ':getAll');
    $app->get('/measures/{date}', Measure::class . ':getDate');
    $app->get('/measures/{from}/{to}', Measure::class . ':getFromTo');
};
