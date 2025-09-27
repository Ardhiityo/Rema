<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    public function index()
    {
        return view('pages.study-program.index');
    }
}
