<?php
declare(strict_types=1);

use App\Application\Object\Callback;
use App\Application\Object\Device;
use Slim\App;

return function (App $app) {
    $app->post('/callback', Callback::class . ':process');

    $app->get('/devices', Device::class . ':getAll');
    $app->get('/devices/{id}', Device::class . ':get');
};
