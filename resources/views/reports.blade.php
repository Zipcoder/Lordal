@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>Student Reports</h1>
		<ul>
			<li><a href="{{Storage::url('student_report_February_26_2019.csv')}}">February 26, 2019</a></li>
			<li><a href="{{Storage::url('student_report_March_06_2019.csv')}}">March 6, 2019</a></li>
			<li><a href="{{Storage::url('student_report_March_15_2019.csv')}}">March 15, 2019</a></li>
			
		</ul>
	</div>
@endsection
