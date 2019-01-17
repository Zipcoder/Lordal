<?php

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('staff', 'HomeController@index')->name('staffportal.home');
Route::get('staff/assessments', 'HomeController@assessments')->name('staffportal.assessments');
Route::get('staff/assessments/new', 'HomeController@create_assessment')->name('staffportal.assessments.new');
Route::get('staff/students', 'HomeController@students')->name('staffportal.students');
Route::get('staff/exams', 'HomeController@exams')->name('staffportal.exams');
Route::get('staff/quizes', 'HomeController@quizes')->name('staffportal.quizes');
Route::get('staff/labs', 'HomeController@labs')->name('staffportal.labs');

Route::get('auth/github', 'Auth\OauthController@redirectToGithubProvider');
Route::get('auth/google', 'Auth\OauthController@redirectToGoogleProvider');
Route::get('auth/google/callback', 'Auth\OauthController@handleProviderGoogleCallback');
Route::get('auth/github/callback', 'Auth\OauthController@handleProviderGithubCallback');

Route::middleware(['auth.student'])->namespace("Pages")->group(function () {
    Route::get('profile', 'LearnerPages@Profile');
});
