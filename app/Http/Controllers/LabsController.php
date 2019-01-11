<?php

namespace App\Http\Controllers;

use App\Assessment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Assessment::labs()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assessment = new Assessment($request->all());
        $assessment->level = 'Lab';
        $assessment->save();
        return response()->json($assessment, Response::HTTP_CREATED);
    }
}
