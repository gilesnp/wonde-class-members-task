<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class EmployeeController extends WondeController
{
    public function employee(Request $request) 
    {
        $errorMessage = false;
        $employee = false;
        $days = false;
        // Get school data from session
        $school = session('school');
        // If user has tried to access URL directly, look in session for employee data
        if (!$request->employee_id) {
            try {
                $employee = session('employee');
                $days = session('days');
                
                // If we have $employee and $days from the session, return the view
                if ($employee && $days) {
                    return view('wonde.employee', [
                        'employee' => $employee,
                        'days' => $days,
                        'errorMessage' => $errorMessage
                    ]);
                }
            } catch (Exception $e) {
                $errorMessage = 'Sorry, employee not found. Please try again.';
                return view('wonde.employee', [
                    'employee' => $employee,
                    'days' => $days,
                    'errorMessage' => $errorMessage
                ]);
            }
        } else {
            // Get employee
            $employee = $school->employees->get($request->employee_id);
            // Check user has classes
            $employeeClasses = $school->employees->get($employee->id, ['classes']);
            if (count($employeeClasses->classes->data) === 0) {
                $errorMessage = 'This user has no classes';
                return view('wonde.employee', [
                    'employee' => $employee,
                    'errorMessage' => $errorMessage
                ]);
            }

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
    }
        
}
