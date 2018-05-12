<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [
            'localeSessionRedirect',
            'localizationRedirect',
            'localeViewPath'
        ]
    ],
    function() {

        Route::get('/', 'HomeController@index')->name('home');

        // search category
        Route::post('/search', 'Category\SearchController@index')->name('category.search');

        Route::get('/category', 'Category\ListController@index')->name('category.list');
        Route::get('/category/{category_slug}', 'Advert\ListController@index')
            ->where('category_slug', '[a-z0-9\-]+')
            ->name('category.show');
        Route::post('/category/{category_slug}/form', 'Advert\ListController@form')
            ->where('category_slug', '[a-z0-9\-]+');
        Route::post('/category/{category_slug}/list', 'Advert\ListController@list')
            ->where('category_slug', '[a-z0-9\-]+');

        Route::group(['prefix' => '/auth', 'namespace' => 'Auth'], function () {
            Route::group(['middleware' => ['guest']], function () {
                // Registration routes
                Route::get('/register', 'RegisterController@form')->name('register');
                Route::post('/register', 'RegisterController@register');

                // Verify email
                Route::get('/verify/{token}', 'RegisterController@verify')->name('verify');

                // Login routes
                Route::get('/login', 'LoginController@showLoginForm')->name('login');
                Route::post('/login', 'LoginController@login')->name('login');

                // OAuth
                Route::get('/login/{provider}', 'LoginProviderController@index')
                    ->name('login.provider');
                Route::get('/login/{provider}/handle', 'LoginProviderController@index')
                    ->name('login.provider.handle');
//                Route::get('/login/{provider}/handle', 'LoginProviderController@index')
//                    ->name('login.provider.handle');
            });

            // Logout routes
            Route::get('/logout', 'LoginController@logout')
                ->middleware(['auth'])
                ->name('logout');
        });

        Route::group(
            [
                'middleware' => ['auth'],
                'prefix' => '/profile',
                'namespace' => 'Profile',
                'as' => 'profile.'
            ],
            function () {
                Route::get('/', 'HomeController@index')->name('home');

                Route::group(['prefix' => '/email', 'as' => 'email.'], function () {
                    Route::get('/', 'EmailResetController@index')->name('form');
                    Route::get('/verify', 'EmailResetController@send')->name('send');
                    Route::put('/verify', 'EmailResetController@verify');
                });

                Route::group(['prefix' => '/password', 'as' => 'password.'], function () {
                    Route::get('/', 'ChangePasswordController@index')->name('form');
                    Route::post('/', 'ChangePasswordController@change');
                });

                Route::group(['prefix' => '/edit', 'as' => 'edit.'], function () {
                    Route::get('/', 'EditController@index')->name('form');
                    Route::post('/', 'EditController@change');
                });

                Route::group(
                    [
                        'prefix' => '/tutor',
                        'namespace' => 'Tutor',
                        'as' => 'tutor.'
                    ],
                    function () {
                        Route::get('/', 'HomeController@index')->name('home');

                        // edit and create tutor profile
                        Route::group(['middleware' => ['can:access-tutor-form']], function () {
                            Route::get('/form', 'EditController@index')->name('form');
                            Route::post('/form', 'EditController@store');
                            Route::put('/form', 'EditController@update');
                        });

                        // verify phone form
                        Route::group(['middleware' => ['can:verify-phone'], 'as' => 'verify.'], function () {
                            Route::get('/verify', 'PhoneController@form')->name('form');
                            Route::post('/verify', 'PhoneController@verify');
                            Route::get('/send-code', 'PhoneController@send')->name('send');
                        });

                        Route::get('/to-moderation', 'EditController@sendToModeration')
                            ->name('moderation')
                            ->middleware(['can:to-moderation']);
                    }
                );
            }
        );

        Route::group(
            [
                'middleware' => [
                    'auth'
                ],
                'prefix' => '/cabinet',
                'namespace' => 'Cabinet',
                'as' => 'cabinet.'
            ],
            function () {
                Route::get('/', 'DefaultController@index')->name('home');

                Route::group(
                    [
                        'middleware' => [
                            'role:client',
                            'can:access-advert'
                        ],
                        'prefix' => '/offer',
                        'namespace' => 'Advert',
                        'as' => 'advert.'
                    ],
                    function () {
                        // list
                        Route::get('/', 'ListController@index')->name('index');
                        Route::delete('/delete/{advert}', 'ListController@destroy');

                        // create
                        Route::get('/create', 'CreateController@selectCategory')->name('create');
                        Route::get('/create/{category}', 'CreateController@createAdvert')->name('create.end');
                        Route::post('/create/{category}', 'CreateController@store');

                        Route::group(['prefix' => '/edit/{advert}'], function () {
                            // update info
                            Route::get('/', 'EditController@edit')->name('update');
                            Route::put('/', 'EditController@update');

                            // Closed advert
                            Route::get('/close', 'CloseController@index')->name('close');

                            // update prices
                            Route::get('/prices', 'PricesController@edit')->name('update.prices');
                            Route::put('/prices', 'PricesController@update');

                            // update files
                            Route::get('/files', 'FilesController@edit')->name('update.files');
                            Route::put('/files', 'FilesController@update');
                            Route::delete('/files/{file}', 'FilesController@delete')->name('delete.file');

                            // update attributes
                            Route::get('/attribute', 'AttributeController@edit')->name('update.attribute');
                            Route::put('/attribute', 'AttributeController@update');

                            // Moderation
                            Route::get('/moderation', 'ModerationController@index')->name('moderation');
                        });
                    }
                );

                Route::group(
                    [
                        'prefix' => '/admin',
                        'namespace' => 'Admin',
                        'as' => 'admin.',
                    ],
                    function () {
                        Route::group(['middleware' => ['role:content']], function () {
                            Route::get('/', 'DefaultController@index')->name('home');
                            Route::resource('/category', 'CategoryController');
                            Route::group(['prefix' => 'category/{category}', 'as' => 'category.'], function () {
                                Route::get('/first', 'CategoryController@first')->name('first');
                                Route::get('/up', 'CategoryController@up')->name('up');
                                Route::get('/down', 'CategoryController@down')->name('down');
                                Route::get('/last', 'CategoryController@last')->name('last');

                                Route::resource('/attribute', 'AttributeController');
                            });

                            Route::get('/translate/generate', 'TranslateController@generate')
                                ->name('translate.generate');
                            Route::resource('/translate', 'TranslateController');
                        });

                        Route::group(['middleware' => ['role:moderator']], function () {
                            Route::resource('/tutor', 'TutorProfileController')
                                ->except(['create', 'show']);

                            Route::resource('/advert', 'AdvertController')
                                ->except(['create', 'show']);
                        });

                        Route::group(['middleware' => ['role:super_admin']], function () {
                            Route::resource('/users', 'UsersController');
                            Route::resource('/role', 'RoleController');
                            Route::resource('/permission', 'PermissionController');
                        });
                    }
                );
            }
        );
    }
);
