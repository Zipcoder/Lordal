<?php

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::namespace("Pages")->group(function () {
	Route::get('/staff/', 'StaffPages@index')->name('staffportal.home');
	Route::get('/staff/assessments', 'StaffPages@assessments')->name('staffportal.assessments');
	Route::get('/staff/assessments/new', 'StaffPages@create_assessment')->name('staffportal.assessments.new');
	Route::get('/staff/students', 'StaffPages@students')->name('staffportal.students');
    Route::get('/staff/students/{student_id}', 'StaffPages@student')->name('staffportal.student');
    Route::get('/staff/students/{student_id}/performance', 'StaffPages@student_breakdown_reports')->name('staffportal.student_breakdown_reports');
	Route::get('/staff/exams', 'StaffPages@exams')->name('staffportal.exams');
	Route::get('/staff/quizzes', 'StaffPages@quizzes')->name('staffportal.quizzes');
	Route::get('/staff/labs', 'StaffPages@labs')->name('staffportal.labs');
	Route::get('/staff/assessment/{assessment_id}', 'StaffPages@updateAssessment')->name('staffportal.assessment.update');
	Route::get('/staff/tokens', 'StaffPages@tokens')->name('staffportal.tokens');
	Route::get('/staff/reports', 'StaffPages@reports')->name('staffportal.reports');
});

Route::get('auth/github', 'Auth\OAuthController@redirectToGithubProvider');
Route::get('auth/google', 'Auth\OAuthController@redirectToGoogleProvider');
Route::get('auth/google/callback', 'Auth\OAuthController@handleProviderGoogleCallback');
Route::get('auth/github/callback', 'Auth\OAuthController@handleProviderGithubCallback');

Route::namespace("Pages")->group(function () {
    Route::get('/student/profile', 'LearnerPages@profile')->name('studentportal.profile');
    Route::get('/student/exams', 'LearnerPages@exams')->name('studentportal.exams');
    Route::get('/student/quizzes', 'LearnerPages@quizzes')->name('studentportal.quizzes');
    Route::get('/student/labs', 'LearnerPages@labs')->name('studentportal.labs');
    Route::get('/student/submission/{assignment_id}', 'LearnerPages@submission')->name('studentportal.submission_form');
});
