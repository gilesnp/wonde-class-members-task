@extends('layouts.layout')

@section('content')

<p class="subtitle">Hello, <strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong>, with employee_id {{ $employee->id }}!</p>

@if (!$errorMessage)

    <div class="columns">
    
        @foreach ($classesWithStudents as $class)
            <div class="column">
                <p class="subtitle"><strong>{{ $class->name }}</strong></p>
                @foreach ($class->lessons->data as $lesson)

                    <p>Lesson period: {{ $lesson->period }}</p>
                    <p>Lesson employee: {{ $lesson->employee }}</p>
                    
                @endforeach
                <ul>

                    @foreach ($class->students->data as $student)
                        <li><i class="fa-solid fa-user"></i> {{ $student->forename }} {{ $student->surname }}</li>
                    @endforeach
                    
                </ul>
            </div>
        @endforeach

    </div>

@else

    <p class="subtitle">{{ $errorMessage }}</p>

@endif

@endsection