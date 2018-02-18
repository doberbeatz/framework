<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

/** @var ContainerBuilder $sc */
$sc = include __DIR__ . '/../src/container.php';
$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Response $response */
$response = $sc->get('framework')->handle($request);
$response->send();