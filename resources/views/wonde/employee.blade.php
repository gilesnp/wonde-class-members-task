@extends('layouts.layout')

@section('content')

<p class="subtitle">Hello, <strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong>, with employee_id {{ $employee->id }}!</p>

@if (!$errorMessage)

    <p>Please select a day to view:</p>
    <form action="/wonde/school/employee" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="select">
            <select name="day">
            
                @foreach ($days as $key => $day)
                    <option value="">
                        {{ ucfirst($key) }}
                    </option>
                @endforeach
                
            </select>
        </div>
        <input type="submit" name="select_day" value="Go" class="button is-success">
    </form>
    
        @foreach ($days as $key => $day)
            <div class="column">
                <p class="subtitle"><strong>{{ ucfirst($key) }}</strong></p>

                @foreach ($day as $period)
                    <p>{{ $period->name }}</p>
                @endforeach

            </div>
        @endforeach

        {{-- @foreach ($classesWithStudents as $class)
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
        @endforeach --}}

@else

    <p class="subtitle">{{ $errorMessage }}</p>

@endif

@endsection