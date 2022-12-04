<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class SchoolController extends WondeController
{
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
                // Reorder employees alphabetically
                $employees = collect($employees)->sortBy('surname')->toArray();
                // If we have $school, $schoolInfo and $employees, return the view
                if ($school && $schoolInfo && $employees) {
                    return view('wonde.school', [
                        'schoolInfo' => $schoolInfo,
                        'employees' => $employees,
                        'errorMessage' => $errorMessage
                    ]);
                }
            } catch (Exception $e) {
                // If no school in session, redirect to home
                return redirect('/wonde');
            }
        } else {
            session(['schoolInfo' => $schoolInfo]);
            session(['school' => $school]);
            // Try to get employees
            try {
                $employees = $school->employees->all();
                dump($employees);
                $employees = collect($employees)->sortBy('surname')->toArray();
                dd($employees);
                session(['employees' => $employees]);
            } catch (Exception $e) {
                $errorMessage = 'Sorry, you do not have access to that school';
                return view('wonde.school', [
                    'schoolInfo' => $schoolInfo,
                    'employees' => $employees,
                    'errorMessage' => $errorMessage
                ]);
            }
            if ($employees) {
                // If we have employees, return the view
                return view('wonde.school', [
                    'schoolInfo' => $schoolInfo,
                    'employees' => $employees,
                    'errorMessage' => $errorMessage
                ]);
            } else {
                // Something else went wrong, so just redirect to home
                return redirect()->route('/');
            }
        }
    }
}
