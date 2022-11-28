<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
 
class WondeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Authorise call with bearer token in header
        $client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
        // Get school data
        $schools = $client->schools->all();
        return view('wonde.index', [
            'schools' => $schools
        ]);
    }

    
    public function school(Request $request)
    {
        $client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
        $school = $client->school($request->school_id);
        $employees = false;
        $errorMessage = false;
        $selectedSchool = false;
        // Try to get employees
        try {
            $employees = $school->employees->all();
        } catch (Exception $e) {
            $errorMessage = 'Sorry, you do not have access to that school.';
            return view('wonde.school', [
                'selectedSchool' => $selectedSchool,
                'employees' => $employees,
                'errorMessage' => $errorMessage
            ]);
        }
        // If we have employees, return the view
        if ($employees) {
            $selectedSchool = $client->schools->get($request->school_id);
            return view('wonde.school', [
                'selectedSchool' => $selectedSchool,
                'employees' => $employees,
                'errorMessage' => $errorMessage
            ]);
        }
    }

    public function employee(Request $request) {
        // Authorise call with bearer token in header
        $client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
        // Get school data
        $school = $client->school(config('wondeConstants.wonde.school_id'));
        // Get employees with their classes
        $employee = $school->employees->get($request->employee_id, ['classes']);
        $errorMessage = false;
        // Loop through classes data from employee object and get classes with students
        if ($employee->classes->data) {
            foreach ($employee->classes->data as $class) {
                $classInfo = $school->classes->get($class->id, ['students','lessons']);
                $classesWithStudents[] = $classInfo;
            }
        } else {
            $classesWithStudents = false;
            $errorMessage = 'No classes to display.';
        }
        
        return view('wonde.employee', [
            'employee' => $employee,
            'classesWithStudents' => $classesWithStudents,
            'errorMessage' => $errorMessage
        ]);
    }
}
