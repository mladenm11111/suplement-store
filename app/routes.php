<?php

$routes = [
    // Pocetna stranica
    '' => ['controller' => 'ProductController', 'method' => 'index'],

    // Proizvodi
    'products' => ['controller' => 'ProductController', 'method' => 'index'],
    'product/id' => ['controller' => 'ProductController', 'method' => 'show'],
    'product/add' => ['controller' => 'ProductController', 'method' => 'add'],
    'product/delete' => ['controller' => 'ProductController', 'method' => 'delete'],
    'product/update' => ['controller' => 'ProductController', 'method' => 'update'],

    // Kategorije
    'categories' => ['controller' => 'CategoryController', 'method' => 'index'],
    'category/add' => ['controller' => 'CategoryController', 'method' => 'add'],
    'category/delete' => ['controller' => 'CategoryController', 'method' => 'delete'],
    'category/update' => ['controller' => 'CategoryController', 'method' => 'update'],

    // Brendovi
    'brands' => ['controller' => 'BrandController', 'method' => 'index'],
    'brand/add' => ['controller' => 'BrandController', 'method' => 'add'],
    'brand/delete' => ['controller' => 'BrandController', 'method' => 'delete'],
    'brand/update' => ['controller' => 'BrandController', 'method' => 'update'],

    // Narudzbine
    'orders' => ['controller' => 'OrderController', 'method' => 'index'],
    'order/id' => ['controller' => 'OrderController', 'method' => 'show'],
    'order/create' => ['controller' => 'OrderController', 'method' => 'create'],
    'order/delete' => ['controller' => 'OrderController', 'method' => 'delete'],
    'order/update' => ['controller' => 'OrderController', 'method' => 'update'],

    // Korisnici
    'users' => ['controller' => 'UserController', 'method' => 'index'],
    'user/id' => ['controller' => 'UserController', 'method' => 'show'],
    'user/delete' => ['controller' => 'UserController', 'method' => 'delete'],
    'user/update' => ['controller' => 'UserController', 'method' => 'update'],

    // Poruke
    'messages' => ['controller' => 'MessageController', 'method' => 'index'],
    'message/id' => ['controller' => 'MessageController', 'method' => 'show'],
    'message/send' => ['controller' => 'MessageController', 'method' => 'send'],
    'message/reply' => ['controller' => 'MessageController', 'method' => 'reply'],
    'message/delete' => ['controller' => 'MessageController', 'method' => 'delete'],

    // Autentifikacija
    'auth/login' => ['controller' => 'AuthController', 'method' => 'login'],
    'auth/register' => ['controller' => 'AuthController', 'method' => 'register'],
    'auth/logout' => ['controller' => 'AuthController', 'method' => 'logout'],

    // Korpa
    'cart' => ['controller' => 'CartController', 'method' => 'index'],
    'cart/add' => ['controller' => 'CartController', 'method' => 'add'],
    'cart/remove' => ['controller' => 'CartController', 'method' => 'remove'],
    'cart/checkout' => ['controller' => 'CartController', 'method' => 'checkout'],

    // Izvestaji (samo admin)
    'reports' => ['controller' => 'ReportController', 'method' => 'index'],
    'report/excel' => ['controller' => 'ReportController', 'method' => 'exportExcel'],
    'report/pdf' => ['controller' => 'ReportController', 'method' => 'exportPdf'],
];