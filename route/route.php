<?php

switch (DEFAULT_MODULE) {
    //index.php入口路由
    case 'home':
        Route::rule(':name/:id$', 'column/content')->pattern(['name' => '[a-zA-Z]+', 'id' => '\d+']);
        Route::rule('search', 'index/search');
        Route::rule('captcha/[:id]', "\\think\\captcha\\CaptchaController@index");
        Route::rule('single/index/<id>', 'single/index')->pattern(['id' => '\d+']);
        Route::rule('Sitemap.xml', 'Seo/Sitemap');
        Route::rule('<name>/<condition?>-<page?>', 'column/index')->pattern(['name' => '[a-zA-Z]+', 'condition' => '[0-9_&=a-zA-Z]+', 'page' => '\d+']);
        Route::rule('/', 'index/index');
        break;
}