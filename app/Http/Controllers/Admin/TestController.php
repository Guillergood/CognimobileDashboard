<?php

namespace App\Http\Controllers\Admin;

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


  public function store(Request $request)
  {
      // Validate the request...

      $test = Test::create($request->all());

      $test->save();

      return "Test created, now you can close this window";
  }


}
