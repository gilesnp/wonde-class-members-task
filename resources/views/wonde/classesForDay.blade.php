@extends('layouts.layout')

@section('content')

<p class="subtitle">Hello, <strong>{{ $employeeWithClasses->title }} {{ $employeeWithClasses->forename }} {{ $employeeWithClasses->surname }}</strong>, with employee_id {{ $employeeWithClasses->id }}!</p>

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

@else

    <p class="subtitle">{{ $errorMessage }}</p>

@endif

@endsection