<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/news', 'news')->name('news');
Route::view('/petition', 'petition')->name('petition');
Route::view('/contact', 'contact')->name('contact');

Route::redirect('/index.html', '/', 301);
Route::redirect('/about.html', '/about', 301);
Route::redirect('/news.html', '/news', 301);
Route::redirect('/petition.html', '/petition', 301);
Route::redirect('/contact.html', '/contact', 301);
