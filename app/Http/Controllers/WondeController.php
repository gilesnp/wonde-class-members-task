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
        // Get employee with classes
        $employee = $school->employees->get($request->employee_id, ['classes.lessons']);
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

        foreach ($days[$dayTitle] as $period) {
            foreach ($period->lessons->data as $lessonPeriod) {
                if ($lessonPeriod->employee === $employee->id) {
                    foreach ($employee->classes->data as $class) {
                        echo $lessonPeriod->period;
                        // dd($class->lessons->data);
                        foreach ($class->lessons->data as $classLesson) {
                            echo $classLesson->period;
                            // dd($classLesson->period);
                            if ($lessonPeriod->period === $classLesson->period) {
                                echo 'hi';
                                die;
                            }
                        }
                        $classDetails = $school->classes->get($class->id, ['students']);
                        foreach ($classDetails->lessons->data as $classLesson) {
                            if ($classLesson->period === $lessonPeriod->period) {
                                echo $employee->id;
                                echo "<br>";
                                echo $employee->title . ". " . $employee->forename . " " . $employee->surname;
                                echo "<br>";
                                echo $period->name;
                                echo "<br>";
                                echo $period->start_time;
                                echo "<br>";
                                echo $period->end_time;
                                echo "<br>";
                                echo $class->name;
                                echo "<br>";
                                echo $class->id;
                                echo "<br><br>"; 
                            }
                        }
                    }
                }
            }
            die;
        }
        die;
        foreach ($employee->classes->data as $class) {
            $classDetails = $school->classes->get($class->id, ['students', 'lessons']);
            foreach ($days[$dayTitle] as $day) {
                // dd($classDetails->lessons->data);
                foreach ($classDetails->lessons->data as $lessonPeriod) {
                    if ($lessonPeriod->period === $day->id && $lessonPeriod->employee === $employee->id) {
                        echo $lessonPeriod->period;
                        echo "<br>";
                        echo $classDetails->name;
                        echo "<br>";
                        echo $day->name;
                        echo "<br><br>";
                    }
                }
            }
        }
        die;

        return view('wonde.classesForDay', [
            'employee' => $employee,
            'days' => $days,
            'dayTitle' => $dayTitle,
            'errorMessage' => $errorMessage
        ]);
        die;

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
    }
}
