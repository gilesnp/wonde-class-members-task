@extends('layouts.layout')

@section('content')

@if (!$errorMessage)

    <p class="subtitle">Hello <strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong>. Please click <a href="/wonde/school">here</a> to choose a different user.</p>
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

@elseif ($errorMessage == 'This user has no classes')

    <p class="subtitle"><strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong></p>
    <p>{{ $errorMessage }}. Please click <a href="/wonde/school">here</a> to try a different user.</p>

@endif

@endsection