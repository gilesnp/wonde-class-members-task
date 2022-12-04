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
        if (!$request->school_id) {
            try {
                // If user has tried to access page directly, look for $school and $schoolInfo in session
                $school = session('school');
                $schoolInfo = session('schoolInfo');
                // Get the employees again to be sure
                $employees = $school->employees->all();
                // If we have $school, $schoolInfo and $employees, return the view
                if ($school && $schoolInfo && $employees) {
                    return view('wonde.school', [
                        'schoolInfo' => $schoolInfo,
                        'employees' => $employees,
                        'errorMessage' => $errorMessage
                    ]);
                }
            } catch (Exception $e) {
                // If no school in session, return error message
                $errorMessage = 'Sorry, you do not have access to that school.';
                return view('wonde.school', [
                    'schoolInfo' => $schoolInfo,
                    'employees' => $employees,
                    'errorMessage' => $errorMessage
                ]);
            }
        }
        session(['schoolInfo' => $schoolInfo]);
        session(['school' => $school]);
        // Try to get employees
        try {
            $employees = $school->employees->all();
            session(['employees' => $employees]);
        } catch (Exception $e) {
            $errorMessage = 'Sorry, you do not have access to that school.';
            return view('wonde.school', [
                'schoolInfo' => $schoolInfo,
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
        // Get employee with classes
        $employee = $school->employees->get($request->employee_id);
        session(['employee' => $employee]);
        // Get all periods for this school with lessons
        $periods = $school->periods->all(['lessons']);
        $days = [];
        if ($periods) {
            foreach ($periods as $period) {
                // If this period has no lessons attached, skip it
                if (count($period->lessons->data) === 0) {
                    continue;
                }
                
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
        
        session(['days' => $days]);
        
        return view('wonde.employee', [
            'employee' => $employee,
            'days' => $days,
            'errorMessage' => $errorMessage
        ]);
    }

    public function classesForDay(Request $request) 
    {
        $dayTitle = $request->day_title;
        $errorMessage = false;
        $school = session('school');
        $employee = session('employee');
        $days = session('days');
        $employeeClasses = $school->employees->get($employee->id, ['classes.lessons.period']);
        $classDetails = [];

        foreach ($days[$dayTitle] as $period) {
            foreach ($employeeClasses->classes->data as $class) {
                foreach ($class->lessons->data as $classLessonData) {
                    if ($period->id === $classLessonData->period->data->id && $employee->id === $classLessonData->employee) {
                        if ($class) {
                            $classDetails[$period->name]['period'][] = $period;
                            // $classDetails[$period->name]['class'][] = $class;
                            $classDetails[$period->name]['students'][] = $school->classes->get($class->id, ['students']);
                        }
                    }
                }
            }
        }

        return view('wonde.classesForDay', [
            'employee' => $employee,
            'days' => $days,
            'classDetails' => $classDetails,
            'dayTitle' => $dayTitle,
            'errorMessage' => $errorMessage
        ]);
    }
}
