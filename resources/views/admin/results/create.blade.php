@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="c-body">
            <div class="fade-in">
                <div class="card mb-0 pl-75 pr-75 pb-75">
                    <h3 class="card-title mt-4 ml-2">Create new test</h3>
                    <div class="column">
                        <h4 class="card-title mt-2 card-footer">Test parameters</h4>
                        <label class="testPadding">
                            Name:
                            <input id="name" type="text" class="ml-3">
                        </label>
                    </div>
                    <div class="column testPadding">
                        <label for="languages">
                            Choose test language
                            <select name="languages" id="languages"></select>
                        </label>
                    </div>
                    <div class="column testPadding">
                        <label for="languages">
                            Should it display help starting the task
                            <select name="display_help" id="display_help">
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </label>
                    </div>
                    <div class="drop_zone">
                        <div id="test">

                        </div>
                        <div class="column"><h4 class="card-title mt-2 card-footer">New Task</h4></div>
                        <div id="container" class="testPadding">

                        </div>
                        <button type="button" class="btn btn-info align-content-center" aria-expanded="false" onclick="createTaskBody()">
                            <i class="fas fa-plus-circle">

                            </i>
                            Create task
                        </button>
                    </div>
                </div>
                <div id="saveFooter" class="card-footer d-none">
                    <div class="row text-center">
                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <button type="button" class="btn btn-info align-content-center" aria-expanded="false" onclick="saveTest()">
                                <i class="fas fa-cloud-upload-alt">

                                </i>
                                Save test
                            </button>
                        </div>
                    </div>
                </div>
                <div id="resultContainer" class="card-footer d-none">
                    <div class="row text-center">

                        <div class="col-sm-12 col-md mb-sm-2 mb-0">
                            <button type="button" class="btn btn-info align-content-center" aria-expanded="false" onclick="copyToClipBoard()">
                                <i class="fas fa-copy">

                                </i>
                                Copy test to clipboard
                            </button>
                        </div>


                    </div>
                    <div class="row text-center">
                        <textarea id="result" class="col-sm-12 col-md mb-sm-2 mb-0" readonly>
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@parent
<script>



    let test;
    let taskNumber = 0;
    let GRAPH = 0;
    let CUBE = 1;
    let WATCH = 2;
    let IMAGE = 3;
    let MEMORY = 4;
    let ATTENTION_NUMBERS = 5;
    let ATTENTION_LETTERS = 6;
    let ATTENTION_SUBTRACTION = 7;
    let LANGUAGE = 8;
    let FLUENCY = 9;
    let ABSTRACTION = 10;
    let RECALL = 11;
    let ORIENTATION = 12;

    const TASKNAME = "task_";

    let TASKSTYPES = new Map();

    TASKSTYPES.set("Alternating Trail Making",GRAPH);
    TASKSTYPES.set("Visuoconstructional Skills (Cube)",CUBE);
    TASKSTYPES.set("Visuoconstructional Skills (Clock)",WATCH);
    TASKSTYPES.set("Naming",IMAGE);
    TASKSTYPES.set("Memory",MEMORY);
    TASKSTYPES.set("Digit Span",ATTENTION_NUMBERS);
    TASKSTYPES.set("Vigilance",ATTENTION_LETTERS);
    TASKSTYPES.set("Serial subtraction",ATTENTION_SUBTRACTION);
    TASKSTYPES.set("Sentence repetition",LANGUAGE);
    TASKSTYPES.set("Verbal fluency",FLUENCY);
    TASKSTYPES.set("Abstraction",ABSTRACTION);
    TASKSTYPES.set("Delayed recall",RECALL);
    TASKSTYPES.set("Orientation",ORIENTATION);

    document.addEventListener("DOMContentLoaded", function() {

        let option;
        var values = ["Alternating Trail Making", "Visuoconstructional Skills (Cube)", "Visuoconstructional Skills (Clock)",
            "Naming", "Memory", "Digit Span", "Vigilance", "Serial subtraction", "Sentence repetition", "Verbal fluency", "Abstraction",
            "Delayed recall", "Orientation"];

        var select = document.createElement("select");
        select.name = "taskTypes";
        select.id = "taskTypes"

        for (const val of values) {
            option = document.createElement("option");
            option.value = val;
            option.text = val.charAt(0).toUpperCase() + val.slice(1);
            select.appendChild(option);
        }

        var label = document.createElement("label");
        label.innerHTML = "Choose which is task type is: "
        label.htmlFor = "taskTypes";

        document.getElementById("container").appendChild(label).appendChild(select);



        var languages = ["ar","ar_EG","bg","bg_BG","ca","ca_ES","cs","cs_CZ","da",
            "da_DK","de","de_AT","de_BE","de_CH","de_DE","de_LI","de_LU","el","el_CY","el_GR",
            "en","en_AU","en_BE","en_BW","en_BZ","en_CA","en_GB","en_HK","en_IE","en_IN","en_JM","en_MH",
            "en_MT","en_NA","en_NZ","en_PH","en_PK","en_RH","en_SG","en_TT","en_US","en_US_POSIX","en_VI",
            "en_ZA","en_ZW","es","es_AR","es_BO","es_CL","es_CO","es_CR","es_DO","es_EC","es_ES","es_GT","es_HN","es_MX",
            "es_NI","es_PA","es_PE","es_PR","es_PY","es_SV","es_US","es_UY","es_VE","et","et_EE","eu",
            "eu_ES","fa","fa_IR","fi","fi_FI","fr","fr_BE","fr_CA","fr_CH","fr_FR","fr_LU","fr_MC","gl",
            "gl_ES","hr","hr_HR","hu","hu_HU","in","in_ID","is","is_IS",
            "it","it_CH","it_IT","iw","iw_IL","ja","ja_JP","kk","kk_KZ",
            "ko","ko_KR","lt","lt_LT","lv","lv_LV","mk","mk_MK","ms","ms_BN","ms_MY","nl","nl_BE","nl_NL","no","no_NO",
            "no_NO_NY","pl","pl_PL","pt","pt_BR","pt_PT","ro","ro_RO","ru","ru_RU","ru_UA","sh","sh_BA","sh_CS","sh_YU",
            "sk","sk_SK","sl","sl_SI","sq","sq_AL","sr","sr_BA","sr_ME","sr_RS",
            "sv","sv_FI","sv_SE","th","th_TH","tr","tr_TR","uk","uk_UA","vi",
            "vi_VN","zh","zh_CN","zh_HK","zh_HANS_SG","zh_HANT_MO","zh_MO","zh_TW"];

        let languagesDropdown = document.getElementById("languages");

        for (const language of languages) {
            option = document.createElement("option");
            option.value = language;
            option.text = language;
            languagesDropdown.appendChild(option);
        }




    });

    function copyToClipBoard() {

        /* Get the text field */
        var copyText = document.getElementById("result");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("Test copied!");

    }

    function deleteTask(parent) {
        let test = parent.parentElement;
        test.removeChild(parent);
    }

    function createTitleSection(parent,taskTitle) {
        let row = document.createElement('div');
        row.className = "row card-footer mr-0 ml-0";


        let title = document.createElement('h4');
        title.className="col-sm card-title taskType";
        title.innerText = taskTitle;

        let deleteButton = document.createElement('button');
        deleteButton.className = "col-sm card-title btn btn-block btn-danger pull-right";
        deleteButton.innerHTML = "<i class=\"fas fa-minus-square\"></i> Delete Task"
        deleteButton.addEventListener("click", function(){deleteTask(parent)});



        row.appendChild(title);
        row.appendChild(deleteButton);
        parent.appendChild(row);


    }

    function createCardForClock(column) {
        let input = document.createElement("input");
        input.className="ml-3 hour";
        input.setAttribute("required", "");
        let label = document.createElement("label")
        label.className = "testPadding";
        label.innerText = "Introduce the hour to be drawn";
        label.appendChild(input)
        column.appendChild(label);
    }

    function createImageCard(parent) {
        let card = document.createElement('div');
        card.className = "col";
        card.innerHTML ="<div class=\"border\"><div id=\"drop_zone\"><button type=\"button\" class=\"btn btn-block btn-info\"><i class=\"fas fa-file-image\"></i>Read image<input type=\"file\" accept=\"image/gif,image/jpg,image/jpeg,image/png\" class=\"hidden_input\" onchange=\"handleFiles(this.files, this)\" required></button><div id=\"status\" class=\"success\">or drag &amp; drop here.</div></div></div>"
        parent.appendChild(card);
        let input = document.createElement("input");
        input.className="ml-3 expected_answer";
        input.setAttribute("required", "");
        let label = document.createElement("label")
        label.className = "testPadding";
        label.innerText = "Name: ";
        label.appendChild(input)
        card.appendChild(label);
    }

    function handleFiles(variable,parent){
        var reader = new FileReader();
        reader.onload = function(file) {
            let img = new Image();
            img.src = file.target.result;
            let targetParent = parent.parentElement.parentElement;
            targetParent.removeChild(targetParent.childNodes[1]);
            targetParent.removeChild(targetParent.childNodes[0]);
            targetParent.appendChild(img);
        };
        reader.readAsDataURL(variable[0]);
    }


    function createCardForImages(column) {
        let row = document.createElement('div');
        row.className = "row";
        let card = document.createElement('div');
        card.className ="row-m-1";
        let addButton = document.createElement('button');
        addButton.className = "btn btn-block btn-info";
        addButton.innerHTML = "<i class=\"fas fa-plus-square\"></i> Add Image"
        addButton.addEventListener("click", function(){createImageCard(row)});


        card.appendChild(addButton);
        column.appendChild(card);
        column.appendChild(row);

        addButton.click();
    }

    function createTextCard(parent) {
        let card = document.createElement('div');
        card.className = "row testPadding";
        parent.appendChild(card);
        let input = document.createElement("input");
        input.className="ml-3 expected_answer";
        input.setAttribute("required", "");
        let label = document.createElement("label")
        label.className = "testPadding";
        label.innerText = "Element: ";
        label.appendChild(input)
        card.appendChild(label);
    }

    function createCardForMemory(column) {
        let row = document.createElement('div');
        row.className = "row";
        let card = document.createElement('div');
        card.className ="row-m-1";
        let addButton = document.createElement('button');
        addButton.className = "btn btn-block btn-info";
        addButton.innerHTML = "<i class=\"fas fa-plus-square\"></i> Add Element"
        addButton.addEventListener("click", function(){createTextCard(row)});

        let input = document.createElement("input");
        input.className="ml-3 times";
        input.setAttribute("required", "");
        let label = document.createElement("label")
        label.className = "testPadding";
        label.innerText = "Repeat times: ";
        label.appendChild(input);


        card.appendChild(addButton);
        card.appendChild(label);
        column.appendChild(card);
        column.appendChild(row);

        addButton.click();
    }

    function createCardForNumbers(column, tag) {
        let row = document.createElement('div');
        row.className = "row " + tag;
        let card = document.createElement('div');
        card.className ="row-m-1";
        let addButton = document.createElement('button');
        addButton.className = "btn btn-block btn-info";
        addButton.innerHTML = "<i class=\"fas fa-plus-square\"></i> Add Element"
        addButton.addEventListener("click", function(){createTextCard(row)});


        card.appendChild(addButton);
        column.appendChild(card);
        column.appendChild(row);

        addButton.click();
    }

    function createCardForFluency(column) {
        let row = document.createElement('div');
        row.className = "row";
        let card = document.createElement('div');
        card.className = "row testPadding";
        column.appendChild(card);

        let input = document.createElement("input");
        input.className="ml-3 expected_answer";
        input.setAttribute("required", "");
        let label = document.createElement("label")
        label.className = "testPadding";
        label.innerText = "Target letter: ";
        label.appendChild(input);

        let inputNumber = document.createElement("input");
        input.className="ml-3 expected_answer";
        input.setAttribute("required", "");
        let labelNumber = document.createElement("label")
        labelNumber.className = "testPadding";
        labelNumber.innerText = "Number words: ";
        labelNumber.appendChild(inputNumber);

        card.appendChild(label);
        card.appendChild(labelNumber);
        column.appendChild(card);
        column.appendChild(row);
    }

    function createCardForSubtraction(column) {
        let row = document.createElement('div');
        row.className = "row";
        let card = document.createElement('div');
        card.className = "row testPadding";
        column.appendChild(card);

        let inputTimes = document.createElement("input");
        inputTimes.className="ml-3 expected_answer";
        inputTimes.setAttribute("required", "");
        let labelTimes = document.createElement("label")
        labelTimes.className = "testPadding";
        labelTimes.innerText = "Times: ";
        labelTimes.appendChild(inputTimes);

        let inputMinuend = document.createElement("input");
        inputMinuend.className="ml-3 expected_answer";
        inputMinuend.setAttribute("required", "");
        let labelMinuend = document.createElement("label")
        labelMinuend.className = "testPadding";
        labelMinuend.innerText = "Minuend: ";
        labelMinuend.appendChild(inputMinuend)

        let inputSubtrahend = document.createElement("input");
        inputSubtrahend.className="ml-3 expected_answer";
        inputSubtrahend.setAttribute("required", "");
        let labelSubtrahend = document.createElement("label")
        labelSubtrahend.className = "testPadding";
        labelSubtrahend.innerText = "Subtrahend: ";
        labelSubtrahend.appendChild(inputSubtrahend);

        card.appendChild(labelTimes);
        card.appendChild(labelMinuend);
        card.appendChild(labelSubtrahend);
        column.appendChild(card);
        column.appendChild(row);
    }

    function addTitleInsideTask(column, titleText) {
        let title = document.createElement('h4');
        title.className="card-title mt-5 testPadding";
        title.innerText = titleText;
        column.appendChild(title);
    }

    function createCardForLetters(column) {
        let row = document.createElement('div');
        row.className = "row";
        let card = document.createElement('div');
        card.className ="row-m-1";
        let addButton = document.createElement('button');
        addButton.className = "btn btn-block btn-info";
        addButton.innerHTML = "<i class=\"fas fa-plus-square\"></i> Add Element"
        addButton.addEventListener("click", function(){createTextCard(row)});

        let input = document.createElement("input");
        input.className="ml-3 times target_letter";
        input.setAttribute("required", "");
        let label = document.createElement("label")
        label.className = "testPadding";
        label.innerText = "Target letter: ";
        label.appendChild(input);


        card.appendChild(addButton);
        card.appendChild(label);
        column.appendChild(card);
        column.appendChild(row);

        addButton.click();
    }

    function createTaskBody() {
        let column = document.createElement('div');
        column.className = "column";
        column.id = taskNumber;

        let taskOption = document.getElementById("taskTypes");
        let taskValue = taskOption.options[taskOption.selectedIndex].value;


        createTitleSection(column,"Task " + (taskNumber+1) +": "+taskValue);

        let value = TASKSTYPES.get(taskValue);
        switch(value){
            case WATCH:
                createCardForClock(column);
                break;
            case IMAGE:
                createCardForImages(column);
                break;
            case MEMORY:
                createCardForMemory(column);
                break;
            case ATTENTION_NUMBERS:
                addTitleInsideTask(column,"Foward list");
                createCardForNumbers(column,"numbers_forward");
                addTitleInsideTask(column,"Backwards list");
                createCardForNumbers(column,"numbers_backward");
                break;
            case ATTENTION_LETTERS:
                createCardForLetters(column);
                break;
            case ATTENTION_SUBTRACTION:
                createCardForSubtraction(column);
                break;
            case LANGUAGE:
                createCardForNumbers(column,"phrases");
                break;
            case FLUENCY:
                createCardForFluency(column);
                break;
            case ABSTRACTION:
                addTitleInsideTask(column,"Similarities list");
                createCardForNumbers(column,"questions");
                addTitleInsideTask(column,"Expected answers list");
                createCardForNumbers(column,"answers");
                break;
            case RECALL:
                createCardForNumbers(column,"recall");
                break;
            case ORIENTATION:
                addTitleInsideTask(column,"Question list");
                createCardForNumbers(column,"questions");
                break;
        }
        taskNumber++;



        document.getElementById("test").appendChild(column);



        let saveFooter = document.getElementById("saveFooter");
        if(saveFooter.className.includes("d-none")){
            saveFooter.classList.add("visible");
            saveFooter.classList.remove("d-none");
        }

    }

    function checkAllInputs() {
        let errors = 0;
        let inputElements = document.getElementsByTagName("input");
        for(let i = 0; i < inputElements.length; ++i){
            if(inputElements[i].value === ""){
                inputElements[i].classList.add("required");
                ++errors;
            }
            else{
                inputElements[i].classList.remove("required");
            }
        }

        if(errors > 0){
            alert("Please fulfill all the values in red");
        }

        return errors;
    }

    function setTaskType(i, taskType) {
        test[TASKNAME + i] = {};
        test[TASKNAME + i]["taskType"] = taskType;
    }

    function addClockParameters(i,taskCards) {
        let value = taskCards.getElementsByClassName("hour");
        test[TASKNAME + i]["hour"] = value[0].value;
    }

    function addImageParameters(i,taskCard) {
        let images = taskCard.getElementsByTagName("img");
        let expected_answers = taskCard.getElementsByClassName("expected_answer");
        let imagesArray = [];
        let answerArray = [];
        test[TASKNAME + i]["images"] = [];
        test[TASKNAME + i]["answer"] = [];
        for(let i = 0; i < images.length; ++i){
            let base64Img = images[i].src.split(",")[1];
            imagesArray.push(base64Img);
            answerArray.push(expected_answers[i].value);
        }
        test[TASKNAME + i]["images"] = imagesArray
        test[TASKNAME + i]["answer"] = answerArray;
    }

    function addMemoryParameters(i, taskCard) {
        let expected_answers = taskCard.getElementsByClassName("expected_answer");
        let times = taskCard.getElementsByClassName("times");
        let wordArray = [];
        test[TASKNAME + i]["words"] = [];
        test[TASKNAME + i]["times"] = times[0].value;
        for(let i = 0; i < expected_answers.length; ++i){
            let answer = expected_answers[i].value;
            wordArray.push(answer);
        }
        test[TASKNAME + i]["words"] = wordArray;
    }

    function addNumbersParameters(i,taskCard) {
        let numbersFoward = taskCard.getElementsByClassName("numbers_forward");
        let numbersFowardChildren = numbersFoward[0].children;
        let numbersFowardCount = numbersFowardChildren.length;
        let allAnswers = taskCard.getElementsByClassName("expected_answer");

        let forwardArray = [];
        let backwardArray = [];
        test[TASKNAME + i]["numbers_forward"] = [];
        test[TASKNAME + i]["numbers_backward"] = [];
        for(let i = 0; i < allAnswers.length; ++i){
            if(i < numbersFowardCount){
                forwardArray.push(parseInt(allAnswers[i].value));
            }
            else{
                backwardArray.push(parseInt(allAnswers[i].value));
            }

        }

        test[TASKNAME + i]["numbers_forward"] = forwardArray
        test[TASKNAME + i]["numbers_backward"] = backwardArray;
    }

    function addLettersParameters(i, taskCard) {
        let expected_answers = taskCard.getElementsByClassName("expected_answer");
        let targetLetter = taskCard.getElementsByClassName("times");
        let lettersArray = [];
        test[TASKNAME + i]["letters"] = [];
        test[TASKNAME + i]["target_letter"] = targetLetter[0].value;
        for(let i = 0; i < expected_answers.length; ++i){
            let answer = expected_answers[i].value;
            lettersArray.push(answer);
        }
        test[TASKNAME + i]["letters"] = lettersArray;
    }

    function addSubtractionParameters(i, taskCard) {
        let inputs = taskCard.getElementsByTagName("input");

        test[TASKNAME + i]["times"] = parseInt(inputs[0].value)-1;
        test[TASKNAME + i]["minuend"] = parseInt(inputs[1].value);
        test[TASKNAME + i]["subtracting"] = parseInt(inputs[2].value);
    }

    function addLanguageParameters(i, taskCard) {
        let expected_answers = taskCard.getElementsByClassName("expected_answer");
        let phrasesArray = [];
        test[TASKNAME + i]["phrases"] = [];
        for(let i = 0; i < expected_answers.length; ++i){
            let answer = expected_answers[i].value;
            phrasesArray.push(answer);
        }
        test[TASKNAME + i]["phrases"] = phrasesArray;
    }

    function addFluencyParameters(i, taskCard) {
        let inputs = taskCard.getElementsByTagName("input");

        test[TASKNAME + i]["target_letter"] = inputs[0].value;
        test[TASKNAME + i]["number_words"] = parseInt(inputs[1].value);
    }


    function addAbstractionParameters(i, taskCard) {
        let questions = taskCard.getElementsByClassName("questions");
        let questionsChildren = questions[0].children;
        let questionsCount = questionsChildren.length;
        let allAnswers = taskCard.getElementsByTagName("input");

        let questionsArray = [];
        let answerArray = [];
        test[TASKNAME + i]["words"] = [];
        test[TASKNAME + i]["answer"] = [];
        for(let i = 0; i < allAnswers.length; ++i){
            if(i < questionsCount){
                questionsArray.push(allAnswers[i].value);
            }
            else{
                answerArray.push(allAnswers[i].value);
            }

        }

        test[TASKNAME + i]["words"] = questionsArray
        test[TASKNAME + i]["answer"] = answerArray;
    }

    function addRecallParameters(i, taskCard) {
        let expected_answers = taskCard.getElementsByClassName("expected_answer");
        let wordArray = [];
        test[TASKNAME + i]["words"] = [];
        for(let i = 0; i < expected_answers.length; ++i){
            let answer = expected_answers[i].value;
            wordArray.push(answer);
        }
        test[TASKNAME + i]["words"] = wordArray;
    }

    function addOrientationParameters(i, taskCard) {
        let expected_answers = taskCard.getElementsByClassName("expected_answer");
        let questionsArray = [];
        test[TASKNAME + i]["questions"] = [];
        for(let i = 0; i < expected_answers.length; ++i){
            let answer = expected_answers[i].value;
            questionsArray.push(answer);
        }
        test[TASKNAME + i]["questions"] = questionsArray;
    }

    function saveTest() {


        test = {};

        let errors = checkAllInputs();

        if(errors === 0) {
            test["name"] = document.getElementById("name").value;

            let languages = document.getElementById("languages");
            let language = languages.options[languages.selectedIndex].value;
            test["language"] = language;

            let displayHelp = document.getElementById("display_help");
            let displayHelpValue = displayHelp.options[displayHelp.selectedIndex].value;
            test["display_help"] = displayHelpValue === "true";

            let taskCards = document.getElementById("test").children;

            for (let i = 0; i < taskCards.length; ++i) {
                let rawTaskType = taskCards[i].getElementsByClassName("taskType");
                let taskValue = rawTaskType[0].innerText.trim().split(":");
                let taskType = TASKSTYPES.get(taskValue[1].trim());
                switch (taskType) {
                    case GRAPH:
                    case CUBE:
                        setTaskType(i,taskType);
                        break;
                    case WATCH:
                        setTaskType(i,taskType);
                        addClockParameters(i,taskCards[i]);
                        break;
                    case IMAGE:
                        setTaskType(i,taskType);
                        addImageParameters(i,taskCards[i]);
                        break;
                    case MEMORY:
                        setTaskType(i,taskType);
                        addMemoryParameters(i,taskCards[i]);
                        break;
                    case ATTENTION_NUMBERS:
                        setTaskType(i,taskType);
                        addNumbersParameters(i,taskCards[i]);
                        break;
                    case ATTENTION_LETTERS:
                        setTaskType(i,taskType);
                        addLettersParameters(i,taskCards[i]);
                        break;
                    case ATTENTION_SUBTRACTION:
                        setTaskType(i,taskType);
                        addSubtractionParameters(i,taskCards[i]);
                        break;
                    case LANGUAGE:
                        setTaskType(i,taskType);
                        addLanguageParameters(i,taskCards[i]);
                        break;
                    case FLUENCY:
                        setTaskType(i,taskType);
                        addFluencyParameters(i,taskCards[i]);
                        break;
                    case ABSTRACTION:
                        setTaskType(i,taskType);
                        addAbstractionParameters(i,taskCards[i]);
                        break;
                    case RECALL:
                        setTaskType(i,taskType);
                        addRecallParameters(i,taskCards[i]);
                        break;
                    case ORIENTATION:
                        setTaskType(i,taskType);
                        addOrientationParameters(i,taskCards[i]);
                        break;
                }

            }

            let resultArea = document.getElementById("resultContainer");

            document.getElementById("result").innerText = JSON.stringify(test);

            if(resultArea.className.includes("d-none")){
                resultArea.classList.add("visible");
                resultArea.classList.remove("d-none");
            }

            //openWindowWithPostRequest();
            createNewTest();

        }




    }

    function openWindowWithPostRequest() {
        let winName = 'downloadTestWindow';
        let winURL='create/download';
        let windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';
        let params = { 'name' : document.getElementById("name").value,'data' :JSON.stringify(test), "_token": $("meta[name='csrf-token']").attr("content")};
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

    function createNewTest() {
        let winName = 'CreateTestWindow';

        // Find everything up to the first slash and save it in a backreference
        regexp = /(\w+:\/\/[^\/]+)\/.*/;

        // Replace the href with the backreference and the new uri
        let winURL = window.location.href.replace(regexp, "$1/admin/tests/create");
        let windowoption='resizable=yes,height=600,width=800,location=0,menubar=0,scrollbars=1';
        let params = { 'name' : document.getElementById("name").value,'data' :JSON.stringify(test), "_token": $("meta[name='csrf-token']").attr("content")};
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





</script>
@endsection
