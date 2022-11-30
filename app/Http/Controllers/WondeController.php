<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
 
class WondeController extends Controller
{
    private $client;
    private $school;

    public function __construct()
    {
        $this->client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
        $this->school = false;
    }

    public function index()
    {
        // Get school data
        $schools = $this->client->schools->all();
        return view('wonde.index', [
            'schools' => $schools
        ]);
    }

    
    public function school(Request $request)
    {
        $this->school = $this->client->school($request->school_id);
        $employees = false;
        $errorMessage = false;
        $selectedSchool = false;
        // Try to get employees
        try {
            $employees = $this->school->employees->all();
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
            $selectedSchool = $this->client->schools->get($request->school_id);
            return view('wonde.school', [
                'selectedSchool' => $selectedSchool,
                'employees' => $employees,
                'errorMessage' => $errorMessage
            ]);
        }
    }

    public function employee(Request $request) {
        $errorMessage = false;
        // Get school data
        $this->school = $this->client->school(config('wondeConstants.wonde.school_id'));
        // Get employee with their classes
        $employee = $this->school->employees->get($request->employee_id, ['classes']);
        // Get all periods for this school
        $periods = $this->school->periods->all();
        $days = [];
        if ($periods) {
            foreach ($periods as $period) {
                switch ($period->day) {
                    case 'monday':
                        $days['monday'][] = $period;
                        break;
                    case 'tuesday':
                        $days['tuesday'][] = $period;
                        break;
                    case 'wednesday':
                        $days['wednesday'][] = $period;
                        break;
                    case 'thursday':
                        $days['thursday'][] = $period;
                        break;
                    case 'friday':
                        $days['friday'][] = $period;
                        break;
                }
            }
        }
        // Loop through classes data from employee object and get classes with students
        if ($employee->classes->data) {
            foreach ($employee->classes->data as $class) {
                $classesWithStudents[] = $this->school->classes->get($class->id, ['students','lessons']);
                foreach ($classesWithStudents as $classWithStudent) {
                    foreach ($classWithStudent->lessons->data as $data) {
                        if ($data->employee === $employee->id) {
                            // var_dump($data->period);
                            // var_dump($employee->id);
                            // die;
                        }
                    }
                    
                }
            }
        } else {
            $days = false;
            $classesWithStudents = false;
            $errorMessage = 'No classes to display.';
        }
        
        return view('wonde.employee', [
            'employee' => $employee,
            'classesWithStudents' => $classesWithStudents,
            'days' => $days,
            'errorMessage' => $errorMessage
        ]);
    }
}
