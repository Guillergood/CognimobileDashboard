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


  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View
   */
  public function index()
  {
      $tests = Test::all();
      return view('admin.tests.index', ['tests' => $tests]);
  }

  public function store(Request $request)
  {
      $test = Test::create($request->all());

      $create = $test->save();

      if ($create){

        $data='Test created, now you can close this window';

      }else{

        $data='An error happened';

      }

      return $data;
  }

  public function destroy(Request $request)
  {

      $test = Test::find($request->id);
      if($test){
          $destroy = Test::destroy($request->id);
      }

      if ($destroy){

        $data = 'Test has been removed';

      }else{

          $data='An error happened';

      }

      return $data;
  }


}
