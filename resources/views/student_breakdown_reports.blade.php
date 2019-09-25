@extends('layouts.app')

@section('content')
    <student-report :student_id="{{$student_id}}"></student-report>
@endsection
