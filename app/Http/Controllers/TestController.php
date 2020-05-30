<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Test;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TestController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $tests = Test::all();
        //$tests = ["a","b","c"];
        return view('tests.index', ['tests' => $tests]);
    }


    /**
     * Display the specified resource.
     *
     * @param Test $test
     * @return Application|Factory|View
     */
    public function show(Test $test)
    {
        //
        return view('tests.show', compact('test'));
    }





}
