<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Exception;
 
class WondeController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
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
        $employees = false;
        $errorMessage = false;
        $schoolInfo = false;
        $schoolInfo = $this->client->schools->get($request->school_id);
        $school = $this->client->school($request->school_id);
        session(['schoolInfo' => $schoolInfo]);
        session(['school' => $school]);
        // Try to get employees
        try {
            $employees = $school->employees->all();
            session(['employees' => $employees]);
        } catch (Exception $e) {
            $errorMessage = 'Sorry, you do not have access to that school.';
            return view('wonde.school', [
                'sc$schoolInfo' => $schoolInfo,
                'employees' => $employees,
                'errorMessage' => $errorMessage
            ]);
        }
        // If we have employees, return the view
        if ($employees) {
            return view('wonde.school', [
                'schoolInfo' => $schoolInfo,
                'employees' => $employees,
                'errorMessage' => $errorMessage
            ]);
        }
    }

    public function employee(Request $request) 
    {
        $errorMessage = false;
        // Get school data from session
        $school = session('school');
        // Get employee with their classes
        $employeeWithClasses = $school->employees->get($request->employee_id, ['classes']);
        session(['employeeWithClasses' => $employeeWithClasses]);
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

    public function classesForDay(Request $request) 
    {
        echo "hi";
    }
}
