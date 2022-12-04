@extends('layouts.layout')

@section('content')

@if (!$errorMessage)

    <p class="subtitle">Hello <strong>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</strong>. Not the right user? Please <a href="/wonde/school">choose a different user</a>.</p>
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

    <article class="message is-warning">
        <div class="message-header">
            <p>{{ $employee->title }} {{ $employee->forename }} {{ $employee->surname }}</p>
        </div>
        <div class="message-body">
            {{ $errorMessage }}. Please <a href="/wonde/school">choose a different user</a>.
        </div>
    </article>

@endif

@endsection