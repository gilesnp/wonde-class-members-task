<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassesController extends Controller
{
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
