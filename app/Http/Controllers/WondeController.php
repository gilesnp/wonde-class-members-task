<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
 
class WondeController extends Controller
{
    protected $client;
    protected $school;
    protected $employee;

    public function __construct()
    {
        $this->client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
        $this->school = false;
        $this->employee = false;
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
        $request->session()->put('school', $this->client->school($request->school_id));
        $school = $request->session()->get('school');
        // $this->school = $this->client->school($request->school_id);
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
        $this->employee = $this->school->employees->get($request->employee_id, ['classes']);
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
        var_dump($this->employee->classes);
        die;
        if ($this->employee->classes->data) {
            foreach ($this->employee->classes->data as $class) {
                $classesWithStudents[] = $this->school->classes->get($class->id, ['students','lessons']);
                foreach ($classesWithStudents as $classWithStudent) {
                    foreach ($classWithStudent->lessons->data as $data) {
                        if ($data->employee === $this->employee->id) {
                            // var_dump($data->period);
                            // var_dump($this->employee->id);
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
            'employee' => $this->employee,
            'classesWithStudents' => $classesWithStudents,
            'days' => $days,
            'errorMessage' => $errorMessage
        ]);
    }
}
