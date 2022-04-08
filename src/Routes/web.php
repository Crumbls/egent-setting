<?php

use Illuminate\Support\Facades\Route;

Route::group([
	'prefix' => 'settings/templates/message-libraries',
	'as' => 'settings.templates.message-libraries.',
//	'middleware' => ['developer']
], function() {
	Route::get('/', \Egent\Setting\Http\Controllers\Template\MessageLibrary\IndexController::class)->name('index');
	Route::get('create', \Egent\Setting\Http\Controllers\Template\MessageLibrary\CreateController::class)->name('create');
	Route::post('', \Egent\Setting\Http\Controllers\Template\MessageLibrary\StoreController::class)->name('store');
	Route::get('{message}', \Egent\Setting\Http\Controllers\Template\MessageLibrary\ShowController::class)->name('show');
	Route::get('{message}/edit', \Egent\Setting\Http\Controllers\Template\MessageLibrary\EditController::class)->name('edit');
	Route::patch('{message}', \Egent\Setting\Http\Controllers\Template\MessageLibrary\UpdateController::class)->name('update');
	Route::get('{message}/delete', \Egent\Setting\Http\Controllers\Template\MessageLibrary\DeleteController::class)->name('delete');
	Route::delete('{message}', \Egent\Setting\Http\Controllers\Template\MessageLibrary\DestroyController::class)->name('destroy');
});
Route::group([
	'prefix' => 'settings/templates/messages',
	'as' => 'settings.templates.messages.',
//	'middleware' => ['developer']
], function() {
	Route::get('/', \Egent\Setting\Http\Controllers\Template\Message\IndexController::class)->name('index');
//	Route::get('create', \Egent\Setting\Http\Controllers\Template\Message\CreateController::class)->name('create');
	Route::post('', \Egent\Setting\Http\Controllers\Template\Message\StoreController::class)->name('store');
//	Route::get('{message}', \Egent\Setting\Http\Controllers\Template\Message\ShowController::class)->name('show');
//	Route::get('{message}/edit', \Egent\Setting\Http\Controllers\Template\Message\EditController::class)->name('edit');
	Route::patch('{message}', \Egent\Setting\Http\Controllers\Template\Message\UpdateController::class)->name('update');
//	Route::get('{message}/delete', \Egent\Setting\Http\Controllers\Template\Message\DeleteController::class)->name('delete');
	Route::delete('{message}', \Egent\Setting\Http\Controllers\Template\Message\DestroyController::class)->name('destroy');
});

Route::group([
	'prefix' => 'settings/messages',
	'as' => 'settings.messages.'
], function() {
	Route::get('/', \Egent\Setting\Http\Controllers\Messaging\IndexController::class)->name('index');
//	Route::get('create', \Egent\Setting\Http\Controllers\Messaging\CreateController::class)->name('create');
//	Route::post('/', \Egent\Setting\Http\Controllers\Messaging\StoreController::class)->name('store');
	Route::post('/', \Egent\Setting\Http\Controllers\Messaging\UpdateController::class)->name('store');
//	Route::get('{message}', \Egent\Setting\Http\Controllers\Messaging\ShowController::class)->name('show');
//	Route::get('{message}/edit', \Egent\Setting\Http\Controllers\Messaging\EditController::class)->name('edit');
//	Route::patch('{message}', \Egent\Setting\Http\Controllers\Messaging\UpdateController::class)->name('update');
//	Route::get('{message}/delete', \Egent\Setting\Http\Controllers\Messaging\DeleteController::class)->name('delete');
//	Route::delete('{message}', \Egent\Setting\Http\Controllers\Messaging\DestroyController::class)->name('destroy');
});


Route::group([
	'prefix' => 'settings/connect',
	'as' => 'settings.connect.'
], function() {
	Route::get('/', \Egent\Setting\Http\Controllers\Connect\IndexController::class)->name('index');
//	Route::get('create', \Egent\Setting\Http\Controllers\Messaging\CreateController::class)->name('create');
//	Route::post('/', \Egent\Setting\Http\Controllers\Messaging\StoreController::class)->name('store');
	Route::post('/', \Egent\Setting\Http\Controllers\Connect\UpdateController::class)->name('store');
	Route::any('/google', \Egent\Setting\Http\Controllers\Connect\GoogleController::class)->name('store.google');
//	Route::get('{message}', \Egent\Setting\Http\Controllers\Messaging\ShowController::class)->name('show');
//	Route::get('{message}/edit', \Egent\Setting\Http\Controllers\Messaging\EditController::class)->name('edit');
//	Route::patch('{message}', \Egent\Setting\Http\Controllers\Messaging\UpdateController::class)->name('update');
//	Route::get('{message}/delete', \Egent\Setting\Http\Controllers\Messaging\DeleteController::class)->name('delete');
//	Route::delete('{message}', \Egent\Setting\Http\Controllers\Messaging\DestroyController::class)->name('destroy');
});

Route::group([
	'prefix' => 'settings/templates',
	'as' => 'settings.templates.',
	'middleware' => ['developer']
], function() {
	Route::get('/', \Egent\Setting\Http\Controllers\Template\IndexController::class)->name('index');
	Route::get('create', \Egent\Setting\Http\Controllers\Template\CreateController::class)->name('create');
	Route::post('', \Egent\Setting\Http\Controllers\Template\StoreController::class)->name('store');
	Route::get('{template}', \Egent\Setting\Http\Controllers\Template\ShowController::class)->name('show');
	Route::get('{template}/edit', \Egent\Setting\Http\Controllers\Template\EditController::class)->name('edit');
	Route::patch('{template}', \Egent\Setting\Http\Controllers\Template\UpdateController::class)->name('update');
	Route::get('{template}/delete', \Egent\Setting\Http\Controllers\Template\DeleteController::class)->name('delete');
	Route::delete('{template}', \Egent\Setting\Http\Controllers\Template\DestroyController::class)->name('destroy');
});


Route::group([
	'prefix' => 'settings/transaction-coordinator',
	'as' => 'settings.transaction-coordinator.'
], function() {
	Route::get('/', \Egent\Setting\Http\Controllers\TransactionCoordinator\IndexController::class)->name('index');
	Route::post('/', \Egent\Setting\Http\Controllers\TransactionCoordinator\UpdateController::class)->name('store');
});

Route::get('settings', \Egent\Setting\Http\Controllers\Common\IndexController::class)->name('settings.index');
Route::post('settings', \Egent\Setting\Http\Controllers\Common\UpdateController::class)->name('settings.sync');
