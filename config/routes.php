<?php
use Cake\Routing\Route\DashedRoute;

$routes->plugin(
    'CsvImporter',
    ['path' => '/csv-importer'],
    function ($routes) {
        $routes->setRouteClass(DashedRoute::class);

        $routes->get('/imports', ['controller' => 'Imports'],'index');
        $routes->get('/imports/tables', ['controller' => 'Imports', 'action' => 'tables'],'tables');
        $routes->get('/imports/fields/{table}', ['controller' => 'Imports', 'action' => 'fields'],'fields')->setPass(['table']);
        $routes->post('/imports/upload',['controller' => 'imports', 'action' => 'upload'],'upload');
    }
);