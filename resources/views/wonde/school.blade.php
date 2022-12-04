@extends('layouts.layout')

@section('content')

@if ($employees)

    <p class="subtitle">Welcome to <strong>{{ $schoolInfo->name }}</strong>. Not the right school? Please <a href="/wonde">choose another school</a>.</p>
    <p>Please select an employee:</p>

    <form action="/wonde/school/employee" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="select">
            <select name="employee_id">
            
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->title . ' ' . $employee->forename . ' ' . $employee->surname }}
                    </option>
                @endforeach
                
            </select>
        </div>
        <input type="submit" name="select_employee" value="Go" class="button is-success">
    </form>

@else

    <article class="message is-danger">
        <div class="message-header">
            <p>Something went wrong</p>
        </div>
        <div class="message-body">
            {{ $errorMessage }}. Please <a href="/wonde">choose another school</a>.
        </div>
    </article>

@endif


@endsection