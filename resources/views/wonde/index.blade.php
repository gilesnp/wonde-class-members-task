@extends('layouts.layout')

@section('content')

<p class="subtitle">Hello! Please select your school:</p>

<form action="/wonde/school" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="select">
        <select name="school_id">
        
            @foreach ($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
            @endforeach
            
        </select>
    </div>
    <input type="submit" name="select_school" value="Go" class="button is-success">
</form>

@endsection