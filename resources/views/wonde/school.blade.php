@extends('layouts.layout')

@section('content')

<p class="subtitle">Welcome to <strong>{{ $selectedSchool->name }}</strong>.</p>
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

@endsection