<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function classesForDay(Request $request) 
    {
        $errorMessage = false;
        $school = session('school');
        $employee = session('employee');
        $days = session('days');
        if (!$request->day_title) {
            // We can't guess what day the user wanted to choose
            return view('wonde.employee', [
                'employee' => $employee,
                'days' => $days,
                'errorMessage' => $errorMessage
            ]);
        }

        $dayTitle = $request->day_title;
        $employeeClasses = $school->employees->get($employee->id, ['classes.lessons.room']);
        if (count($employeeClasses->classes->data) === 0) {
            $errorMessage = 'This user has no classes';
            return view('wonde.classesForDay', [
                'employee' => $employee,
                'errorMessage' => $errorMessage
            ]);
        }
        if ($employeeClasses) {
            $classDetails = [];

            foreach ($days[$dayTitle] as $period) {
                foreach ($employeeClasses->classes->data as $class) {
                    foreach ($class->lessons->data as $classLessonData) {
                        if ($period->id === $classLessonData->period && $employee->id === $classLessonData->employee) {
                            if ($class) {
                                $classDetails[$period->name]['period'][] = $period;
                                $classDetails[$period->name]['roomCode'] = $classLessonData->room->data->code;
                                $classDetails[$period->name]['roomName'] = $classLessonData->room->data->name;
                                $classesWithStudents = $school->classes->get($class->id, ['students']);
                                $classesWithStudents->students->data = collect($classesWithStudents->students->data)
                                    ->sortBy('surname')
                                    ->toArray();
                                $classDetails[$period->name]['students'][] = $classesWithStudents;
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
}
