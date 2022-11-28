@extends('layouts.layout')

@section('content')

<p class="subtitle">Hello, <strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong>!</p>

@if (!$errorMessage)

    <div class="columns">
    
        @foreach ($classesWithStudents as $class)
            <div class="column">
                <p class="subtitle"><strong>{{ $class->name }}</strong></p>
                <ul>

                    @foreach ($class->students->data as $student)
                        <li><i class="fa-solid fa-user"></i> {{ $student->forename }} {{ $student->surname }}</li>
                    @endforeach
                    
                </ul>
            </div>
        @endforeach

    </div>

@else

    <p class="subtitle"><strong>{{ $errorMessage }}</strong></p>

@endif

@endsection