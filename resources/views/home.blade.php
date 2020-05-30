@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
      <div class="content">
          <div class="c-body">
              <div class="fade-in">
                  <div class="card">
                        <div class="d-flex justify-content-between">
                            <div id="studyCard" class="card mb-0 pl-75 pr-75 pb-75">


                            </div>

                            <div class="d-none" id="study">{{$study}}</div>
                            <div class="d-none" id="sensors">{{$sensors}}</div>
                            <div class="d-none" id="plugins">{{$plugins}}</div>
                        </div>
                        <button type="button" class="btn btn-info" aria-expanded="false" onclick="saveConfigFile()">
                          <i class="fas fa-cloud-upload-alt">

                          </i>
                          Save Config
                        </button>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

<script>



function saveConfigFile(){
  let studiesValues = document.getElementsByClassName("studiesValues");
  let researchValues = document.getElementsByClassName("researcherValues");
  let defaultValues = document.getElementsByClassName("defaultValue");

  let packedStudiesValues="";
  let packedResearchValues="";
  let packedDefaultValues="";

  for(let i = 0; i < studiesValues.length; ++i) {
    if(studiesValues.innerText !== undefined)
      packedStudiesValues+=studiesValues[i].innerText+",";
    else
      packedStudiesValues+=studiesValues[i].value+",";
  }
  for(let i = 0; i < researchValues.length; ++i) {
      packedResearchValues+=researchValues[i].value+",";
  }
  for(let i = 0; i < defaultValues.length; ++i) {
      packedDefaultValues+=defaultValues[i].value+",";
  }

  packedStudiesValues = packedStudiesValues.slice(0, -1);
  packedResearchValues = packedResearchValues.slice(0, -1);
  packedDefaultValues = packedDefaultValues.slice(0, -1);

  let winName = 'SaveStudyConfigWindow';

  // Find everything up to the first slash and save it in a backreference
  regexp = /(\w+:\/\/[^\/]+)\/.*/;

  // Replace the href with the backreference and the new uri
  let winURL = window.location.href.replace(regexp, "$1/admin/save");
  let windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';
  let params = { 'studiesValues' : packedStudiesValues,'researchValues' :packedResearchValues, 'defaultValues' :packedDefaultValues, "_token": $("meta[name='csrf-token']").attr("content")};
  let form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", winURL);
  form.setAttribute("target",winName);
  for (let i in params) {
      if (params.hasOwnProperty(i)) {
          let input = document.createElement('input');
          input.type = 'hidden';
          input.name = i;
          input.value = params[i];
          form.appendChild(input);
      }
  }
  document.body.appendChild(form);
  window.open('', winName,windowoption);
  form.target = winName;
  form.submit();
  document.body.removeChild(form);

}

var studyCard;
var studyCardBody;



document.addEventListener("DOMContentLoaded", function() {
  studyCard = document.getElementById("studyCard");
  let study = JSON.parse(document.getElementById("study").innerText);
  let sensors = JSON.parse(document.getElementById("sensors").innerText);
  let plugins = JSON.parse(document.getElementById("plugins").innerText);
  createTabForStudy();
  traverse(study);
  createTabForSensors();
  traverse(sensors);
  createTabForPlugins();
  traverse(plugins);
});



function createTabForStudy(){
  studyCardBody = document.createElement("div");
  studyCardBody.className = "card-body";
  let title = document.createElement('h3');
  title.className="col-sm card-title taskType";
  title.innerText = "Study";
  studyCardBody.appendChild(title);
  studyCard.appendChild(studyCardBody);
}

function createTabForSensors(){
  studyCardBody = document.createElement("div");
  studyCardBody.className = "card-body";
  let title = document.createElement('h3');
  title.className="col-sm card-title taskType";
  title.innerText = "Sensors";
  studyCardBody.appendChild(title);
  studyCard.appendChild(studyCardBody);
}

function createTabForPlugins(){
  studyCardBody = document.createElement("div");
  studyCardBody.className = "card-body";
  let title = document.createElement('h3');
  title.className="col-sm card-title taskType";
  title.innerText = "Plugins";
  studyCardBody.appendChild(title);
  studyCard.appendChild(studyCardBody);
}

function traverse(data) {

    for (let index in data) {
        if (!!data[index] && typeof(data[index])=="object") {
          traverse(data[index]);
        }
        else{
          let row = document.createElement('div');
          row.className = "row card-footer mr-0 ml-0 config";

          var append = true;
          var label;
          var title;


          if(typeof data[index] !== 'boolean'){
            switch (index) {
              case "package_name":
              case "plugin":
              case "title":
                let titleSubRow = document.createElement('h5');
                titleSubRow.className="col-sm card-title taskType";
                titleSubRow.innerText = data[index];
                row.className = "row card-footer mr-0 ml-0 mt-5 config";
                row.appendChild(titleSubRow);
                break;
              case "summary":
                let explanation = document.createElement('div');
                explanation.className="col-sm card-title taskType";
                explanation.innerText = "Summary: " +data[index];
                row.className = "row mr-0 ml-0 mt-5 config text-muted";
                row.appendChild(explanation);
                break;
              case "sensor":
              case "setting":
              case "icon":
                append = false;
                break;
              case "defaultValue":
              if(typeof data[index] !== 'boolean' && data[index] !== "false" && data[index] !== "true"){
                let input = document.createElement("input");
                input.className="ml-3 value defaultValue";
                input.setAttribute("required", "");
                input.value = data[index];
                label = document.createElement("label")
                label.className = "testPadding";
                label.innerText = "Value: ";
                label.appendChild(input);
                row.appendChild(label);
              }
              else{
                createBinaryDropdown(row,label,data[index],false);
              }
              break;
              default:
                title = document.createElement('h4');
                title.className="col-sm card-title taskType";
                title.innerText = index;
                row.appendChild(title);
                if(index == "study_start"){
                  let div = document.createElement("input");
                  div.setAttribute("readonly", true);
                  div.value = Date.now();
                  div.className="ml-3 value studiesValues";
                  row.appendChild(div);
                }
                else{
                  let input = document.createElement("input");
                  input.setAttribute("required", "");
                  input.value = data[index];
                  label = document.createElement("label")
                  label.className = "testPadding";
                  label.innerText = "Value: ";

                  if(index.includes("study_")){
                    input.className="ml-3 value studiesValues";
                  }
                  else if(index.includes("researcher_")){
                    input.className="ml-3 value researcherValues";
                  }
                  label.appendChild(input);
                  row.appendChild(label);

                }

            }

          }
          else{
            title = document.createElement('h4');
            title.className="col-sm card-title taskType";
            title.innerText = index;
            row.appendChild(title);
            if(index.includes("study_")){
              createBinaryDropdown(row,label,data[index],true);
            }
            else{
              createBinaryDropdown(row,label,data[index],false);
            }
          }



          if(append)
            studyCard.appendChild(row);
        }
    }


}

function createBinaryDropdown(row,label,value,isStudyValue){
  let input = document.createElement("select");
  if(isStudyValue)
    input.className="ml-3 value studiesValues";
  else
    input.className="ml-3 value defaultValue";

  let trueOption = document.createElement("option");
  trueOption.value = true;
  trueOption.text = "true";

  let falseOption = document.createElement("option");
  falseOption.value = false;
  falseOption.text = "false";



  if(value === true || value === "true"){
    trueOption.setAttribute('selected', true);
  }
  else{
    falseOption.setAttribute('selected', true);
  }


  input.appendChild(trueOption);
  input.appendChild(falseOption);

  label = document.createElement("label")
  label.className = "testPadding";
  label.innerText = "Value: ";
  label.appendChild(input);
  row.appendChild(label);
}

</script>

@endsection
