<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class HomeController
{
    public function index()
    {
        $aware = Storage::disk('aware')->get('aware-config.json');
        $json = json_decode($aware);
        //dd($json);
        $study = json_encode($json->study);
        $sensors = json_encode($json->sensors);
        $plugins = json_encode($json->plugins);
        return view('home',compact('study','sensors','plugins'));
    }

    public function saveConfigFile(Request $request)
    {
        $aware = Storage::disk('aware')->get('aware-config.json');
        $json = json_decode($aware,true);
        //dd($json);
        $studiesValues = explode( ',', $request->studiesValues );
        $researchValues = explode( ',', $request->researchValues );
        $defaultValues = explode( ',', $request->defaultValues );

        $i = 0;
        $j = 0;
        $k = 0;



        foreach($json["study"] as $key => $value) {
            if(strpos($key, 'study_') !== false){
              $json["study"][$key] = $studiesValues[$i];
              $i++;
            }
            else if (strpos($key, 'research_') !== false){
              $json["study"][$key] = $researchValues[$j];
              $j++;
            }
        }




        foreach($json["sensors"] as $key => $value) {
            foreach ($json["sensors"][$key]["settings"] as $settingsKey => $value) {
              $json["sensors"][$key]["settings"][$settingsKey]["defaultValue"] = $defaultValues[$k];
              $k++;
            }
        }

        foreach($json["plugins"] as $key => $value) {
            foreach ($json["plugins"][$key]["settings"] as $settingsKey => $value) {
              $json["plugins"][$key]["settings"][$settingsKey]["defaultValue"] = $defaultValues[$k];
              $k++;
            }
        }



        //dd(json_encode($temp, JSON_PRETTY_PRINT));


        Storage::disk('aware')->delete('aware-config.json');
        $update = Storage::disk('aware')->put('aware-config.json',json_encode($json, JSON_PRETTY_PRINT));
        if ($update){

          $data='Config replaced';

        }else{

          $data='An error happened';

        }

        return $data;
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
}
