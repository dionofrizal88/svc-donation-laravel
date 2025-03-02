<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class StudentCardController extends Controller
{
    public function index()
    {
        return view('student-card.form');
    }

    public function generatePDF(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|max:20',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $student_number = str_pad(rand(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT);
        $formatted_student_number = implode(' ', str_split($student_number, 4));
        $path = $request->file('photo')->store('public/photo');
        $pdf = PDF::loadView('student-card.card', [
            'name' => $request->name,
            'student_number' => $request->student_number,
            'photo' => $path,
            'card_number' => $formatted_student_number,
        ]);

        $streamName = 'student_card.pdf';

        return $pdf->stream($streamName);
    }
}
