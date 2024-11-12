<?php
declare(strict_types=1);

use iutnc\nrv\dispatch\Dispatcher;

require_once __DIR__ . '/../../vendor/autoload.php';

iutnc\nrv\repository\Repository::setConfig(__DIR__ . '/../../config/nrv.db.ini');

$d = new Dispatcher();

$d->run();

