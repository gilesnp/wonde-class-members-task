<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
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
