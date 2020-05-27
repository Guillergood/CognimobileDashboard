<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\MassDestroyResultRequest;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class ResultsController extends Controller
{
    public function index()
    {
        //abort_unless(\Gate::allows('result_access'), 403);

        $results = Result::all();


        return view('admin.results.index', ['results' => $results]);
    }

    public function update(UpdateResultRequest $request, Result $result)
    {
        //abort_unless(\Gate::allows('result_edit'), 403);

        $result->update($request->all());

        return redirect()->route('admin.results.index');
    }


    public function create()
    {
        //abort_unless(\Gate::allows('result_create'), 403);

        return view('admin.results.create');
    }

    public function store(StoreResultRequest $request)
    {
        //abort_unless(\Gate::allows('result_create'), 403);

        $result = Result::create($request->all());

        return redirect()->route('admin.results.index');
    }


    public function destroy(Result $result)
    {
        abort_unless(\Gate::allows('result_delete'), 403);

        $result->delete();

        return back();
    }

    public function massDestroy(MassDestroyResultRequest $request)
    {
        Result::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function edit(Request $request){

        $value = false;
        $id = $request->input('id');
        $data = $request->input('data');


        DB::table('results')
            ->where('_id',$id)
            ->update(array(
            'data'=>$data,
        ));

        if(strlen($data) > 0){
            $value = true;
        }


        return response()->json($value);
    }

    public function download(Request $request){

        $filename = $request->input('name');
        $data = $request->input('data');


        return response()->attachment($filename,$data);
    }


}
