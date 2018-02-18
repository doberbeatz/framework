<?php

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation;
use Symfony\Component\DependencyInjection;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

$sc = new DependencyInjection\ContainerBuilder();

$sc->setParameter('debug', true);
$sc->setParameter('charset', 'UTF-8');
$sc->setParameter('routes', include __DIR__.'/../src/app.php');

$sc->register('context', Routing\RequestContext::class);
$sc->register('matcher', Routing\Matcher\UrlMatcher::class)
    ->setArguments(['%routes%', new Reference('context')])
;
$sc->register('request_stack', HttpFoundation\RequestStack::class);
$sc->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
$sc->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

$sc->register('listener.router', HttpKernel\EventListener\RouterListener::class)
    ->setArguments(array(new Reference('matcher'), new Reference('request_stack')))
;
$sc->register('listener.response', HttpKernel\EventListener\RouterListener::class)
    ->setArguments(['%charset%'])
;
$sc->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
    ->setArguments(array('Calendar\Controller\ErrorController::exceptionAction'))
;
$sc->register('listener.string_response', \Simplex\StringResponseListener::class);
$sc->register('dispatcher', Symfony\Component\EventDispatcher\EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
    ->addMethodCall('addSubscriber', [new Reference('listener.string_response')])
;
$sc->register('framework', \Simplex\Framework::class)
    ->setArguments([
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver'),
    ])
;

return $sc;