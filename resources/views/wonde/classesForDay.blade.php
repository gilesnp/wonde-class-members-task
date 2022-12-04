@extends('layouts.layout')

@section('content')

<p class="subtitle">Hello <strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong>.</p>

@if (!$errorMessage)

    <p>Please select a day to view:</p>
    <form action="/wonde/school/employee/classesForDay" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="select">
            <select name="day_title">
            
                @foreach ($days as $key => $day)
                    <option value="{{ $key }}">
                        {{ ucfirst($key) }}
                    </option>
                @endforeach
                
            </select>
        </div>
        <input type="submit" name="select_day" value="Go" class="button is-success">
    </form>

    <p class="subtitle pt-4">Here are your classes for <strong>{{ ucfirst($dayTitle) }}</strong>:</p>

    <div class="columns">

        @foreach ($classDetails as $class)
            <div class="column">
        
                @foreach ($class['period'] as $period)
                    <p><strong>{{ $period->name }}</strong></p>
                    <p>{{ substr($period->start_time, 0, -3) }} - {{ substr($period->end_time, 0, -3) }}</p>
                @endforeach
                
                @foreach ($class['students'] as $classWithStudents)
                    <p class="subtitle"><strong>{{ $classWithStudents->name }}</strong></p>
                    
                    @foreach ($classWithStudents->students->data as $student)
                        <p><i class="fa-solid fa-user"></i> {{ $student->surname }}, {{ $student->forename }}</p>
                    @endforeach

                @endforeach

            </div>
        @endforeach
        
    </div>
@else

    <p class="subtitle">{{ $errorMessage }}</p>

@endif

@endsection