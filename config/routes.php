<?php


use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\BlogController;

$routes = new RouteCollection();

$routes->add('blog_list',
    new Route('/blog',
        array(
            '_controller' => [BlogController::class, 'list']
        ),array(
            'page' => '\d+'
        )
    )
);

return $routes;