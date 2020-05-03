var displayData;
var testCard;
var testCardBody;

function onDropDown(buttonPressed, data) {



    let parent = buttonPressed.parentElement;
    displayData = parent.children[1];
    if(displayData.className.includes("visible")){
        displayData.className = "d-none";
    }
    else{
        displayData.className = "visible";
    }

    testCard = document.createElement("div");
    testCard.className = "card";
    testCardBody = document.createElement("div");
    testCardBody.className = "card-body";
    testCard.appendChild(testCardBody);


    traverse(data,displayData);

    displayData.appendChild(testCard);


}


function drawCircle(x, y, ctx, label, pressed) {
    ctx.moveTo(x,y);
    ctx.beginPath();
    ctx.arc(x, y, 16, 0, 2 * Math.PI, false);

    if(pressed) {
        ctx.fillStyle = "rgb(0,0,0)"
    }
    else{
        ctx.fillStyle = "rgb(255,255,255)"
    }
    ctx.fill();

    ctx.strokeStyle = "black";
    ctx.stroke();


    ctx.font = '8pt Calibri';
    if(pressed) {
        ctx.fillStyle = 'white';
    }
    else{
        ctx.fillStyle = 'black';
    }

    ctx.textAlign = 'center';
    ctx.fillText(label, x, y+3);
}


function drawLine(ctx,points, index) {
    if((index*2)+2 < points.length) {
        if(points[index * 2] > 0 && points[index * 2 + 2] > 0) {
            ctx.beginPath();
            ctx.moveTo(points[index * 2], points[(index * 2) + 1]);
            ctx.lineTo(points[(index * 2) + 2], points[(index * 2) + 3]);
            ctx.stroke();


            ctx.font = '8pt Calibri';
            ctx.fillStyle = 'black';
            ctx.textAlign = 'center';
            ctx.fillText(index+1, (points[index * 2] + points[(index * 2) + 2])/2 + 3, (points[(index * 2) + 1] + points[(index * 2) + 3])/2);

        }
    }
}

function buildPath(ctx,erasedPaths,redColored){

    for(let i = 0; i < erasedPaths.length;i+=4){
        ctx.beginPath();
        ctx.moveTo(erasedPaths[i], erasedPaths[i+1]);
        ctx.lineTo(erasedPaths[i+2], erasedPaths[i+3]);
        if(redColored)
            ctx.strokeStyle = "#FF0000";
        ctx.stroke();
        ctx.closePath();
        ctx.font = '8pt Calibri';
        ctx.fillStyle = 'black';
        ctx.textAlign = 'center';
        ctx.fillText("Correction: "+ ((i/4)+1), (erasedPaths[i]+ erasedPaths[i+2])/2 - 5, (erasedPaths[i+1] + erasedPaths[i+3])/2);

    }

    ctx.strokeStyle = "#000000";

}

function showUserHasNotCompletedTask(column) {
    let text = document.createElement('p');
    text.innerText = "The task was not started by the user.";
    column.appendChild(text);
}

function createTitleSection(parent,taskTitle) {
    let title = document.createElement('h4');
    title.className="card-title mt-5 card-footer";
    title.innerText = taskTitle;
    parent.appendChild(title);
}

function enableEditMode(button) {
    let text = button.parentElement.childNodes[1];
    let saveButton = button.parentElement.childNodes[3];
    saveButton.classList.remove("d-none");
    saveButton.classList.add("visible");
    text.contentEditable = 'true';
    text.classList.add("editable");
    button.classList.add("d-none");
}

function disableEditMode(button) {
    let text = button.parentElement.childNodes[1];
    let editButton = button.parentElement.childNodes[2];
    editButton.classList.remove("d-none");
    editButton.classList.add("visible");
    text.contentEditable = 'false';
    text.classList.remove("editable");
    button.classList.add("d-none");
}

function createScoreSection(parent, score) {
    let row = document.createElement('div');
    row.className = 'row card-title m-0 testPadding';

    let scoreText = document.createElement('h4');
    scoreText.className="card-title mb-0 ";
    scoreText.innerText = "Score: ";

    let scoreValue = document.createElement('h4');
    scoreValue.className="card-title mb-0 ml-1";
    scoreValue.innerText = score !== undefined ? score:"0";

    let editButton = document.createElement('button');
    editButton.className = "btn btn-secondary ml-2"
    editButton.innerText = "Edit"
    editButton.addEventListener("click", function(){enableEditMode(this)});

    let saveButton = document.createElement('button');
    saveButton.className = "btn btn-secondary ml-2 d-none"
    saveButton.innerText = "Save"
    saveButton.addEventListener("click", function(){disableEditMode(this)});


    row.appendChild(scoreText);
    row.appendChild(scoreValue);
    row.appendChild(editButton);
    row.appendChild(saveButton);
    parent.appendChild(row);
}



function createTabForGraph(testCardBody, graphTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column mb-5";

    createTitleSection(column,taskTitle);

    let canvasElement = document.createElement('canvas');
    canvasElement.height= 480;
    canvasElement.width = 480;

    let ctx = canvasElement.getContext("2d");

    let offsetX = 0.09;
    let offsetY = 0.07;

    let proportionX = canvasElement.width/graphTaskInfo.width;
    let proportionY = canvasElement.height/graphTaskInfo.height;

    let xArray = [0.30859375, 0.4638672, 0.5703125, 0.453125, 0.57421875, 0.3544922, 0.3857422, 0.14355469, 0.13378906, 0.27148438];
    let yArray = [0.42083335, 0.11666667, 0.2208333, 0.375, 0.60694444, 0.73055553, 0.55277777, 0.65694445, 0.30972224, 0.0902778];
    let points = [];
    let pattern = [];
    let answer = [];
    let erasedPaths=[];

    for(let i = 0; i < graphTaskInfo.points_sequence.length; i+=2){
        points.push(graphTaskInfo.points_sequence[i]*(proportionX-offsetX));
        points.push(graphTaskInfo.points_sequence[i+1]*(proportionY+offsetY));
    }

    for(let i = 0; i < graphTaskInfo.pattern_sequence.length; ++i){
        pattern.push(graphTaskInfo.pattern_sequence[i]);
    }

    for(let i = 0; i < graphTaskInfo.answer_sequence.length; ++i){
        answer.push(parseInt(graphTaskInfo.answer_sequence[i]));
    }

    for(let i = 0; i < graphTaskInfo.erased_paths.length; i+=2){
        erasedPaths.push(graphTaskInfo.erased_paths[i]*(proportionX-offsetX));
        erasedPaths.push(graphTaskInfo.erased_paths[i+1]*(proportionY+offsetY));
    }

    if(answer.length > 0) {
        buildPath(ctx, erasedPaths, true);

        for (let i = 0, k = 0; i < answer.length; ++i, ++k) {
            let pointLocation = pattern.indexOf(answer[i]);
            let label;
            if (answer[i] % 2 === 0) {
                label = String.fromCharCode(65 + ((answer[i] / 2) - 1));
            } else {
                label = Math.ceil(answer[i] - (answer[i] / 2));
            }


            drawLine(ctx, points, k);
            drawCircle(xArray[pointLocation] * canvasElement.width, yArray[pointLocation] * canvasElement.height, ctx, label, true);

            xArray.splice(pointLocation, 1);
            yArray.splice(pointLocation, 1);
            pattern.splice(pointLocation, 1);
        }

        drawLine(ctx, points, answer.length);


        for (let i = 0; i < pattern.length; ++i) {
            let label;
            if (pattern[i] % 2 === 0) {
                label = String.fromCharCode(65 + ((pattern[i] / 2) - 1));
            } else {
                label = Math.ceil(pattern[i] - (pattern[i] / 2));
            }
            drawCircle(xArray[i] * canvasElement.width, yArray[i] * canvasElement.height, ctx, label, false);
        }


        column.appendChild(canvasElement);
        createScoreSection(column,graphTaskInfo.score);
    }
    else{
        showUserHasNotCompletedTask(column);
    }

    testCardBody.appendChild(column);
}



function createTabForDrawing(testCardBody, drawTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let canvasElement = document.createElement('canvas');
    canvasElement.height= 480;
    canvasElement.width = 480;

    let ctx = canvasElement.getContext("2d");

    let offsetX = 0.09;
    let offsetY = 0.07;

    let proportionX = canvasElement.width/drawTaskInfo.width;
    let proportionY = canvasElement.height/drawTaskInfo.height;

    let pointsDrawn = [];

    for(let i = 0; i < drawTaskInfo.points_sequence.length; i+=2){
        pointsDrawn.push(drawTaskInfo.points_sequence[i]*(proportionX-offsetX));
        pointsDrawn.push(drawTaskInfo.points_sequence[i+1]*(proportionY+offsetY));
    }




    if(pointsDrawn.length > 0){
        for (let i = 0, k = 0; i < pointsDrawn.length;++i,++k) {
            drawLine(ctx,pointsDrawn,k);
        }
        drawLine(ctx,pointsDrawn,pointsDrawn.length);
        column.appendChild(canvasElement);
    }
    else{
        showUserHasNotCompletedTask(column);
    }


    createScoreSection(column,drawTaskInfo.score);


    testCardBody.appendChild(column);
}

function createImagesAnswer(testCardBody, imageTaskInfo, taskTitle) {
    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let answer = [];
    let expectedAnswer = [];


    for(let i = 0; i < imageTaskInfo.answer_sequence.length; ++i){
        answer.push(imageTaskInfo.answer_sequence[i]);
    }

    for(let i = 0; i < imageTaskInfo.expected_answers.length; ++i){
        expectedAnswer.push(imageTaskInfo.expected_answers[i]);
    }


    if(answer.length > 0){
        let row = document.createElement('div');
        row.className = "row testPadding";

        for(let i = 0; i < expectedAnswer.length; ++i) {
            let card = document.createElement('div');
            card.className = "col-sm-6 col-lg-3";


            let answerText = document.createElement('h4');


            answerText.innerText = i < answer.length ? "Answer : "+ answer[i]: "Answer : ";


            let expectedAnswerText = document.createElement('h4');

            expectedAnswerText.innerText = "Expected answer : "+ expectedAnswer[i];

            card.appendChild(answerText);
            card.appendChild(expectedAnswerText);
            row.appendChild(card);
            column.appendChild(row);
        }


    }
    else{
        showUserHasNotCompletedTask(column);
    }



    createScoreSection(column,imageTaskInfo.score);


    testCardBody.appendChild(column);
}

function createMemoryTab(testCardBody, memoryTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let row = document.createElement('div');
    row.className = "row testPadding";
    let card = document.createElement('div');
    card.className = "col-sm-6 col-lg-3";

    let answerText = document.createElement('h4');
    let expectedAnswerText = document.createElement('h4');

    answerText.innerText = "Answer: " + decodeURI(memoryTaskInfo.words.toString());
    expectedAnswerText.innerText = "Answer: " + decodeURI(memoryTaskInfo.expected_answers.toString());

    card.appendChild(answerText);
    card.appendChild(expectedAnswerText);
    row.appendChild(card);
    column.appendChild(row);


    createScoreSection(column,"This task does not have score");


    testCardBody.appendChild(column);
}

function createNumbersCard(testCardBody, numberTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let row = document.createElement('div');
    row.className = "row testPadding";
    let card = document.createElement('div');
    card.className = "col-sm-6 col-lg-3";

    let answerText = document.createElement('h4');
    let expectedAnswerText = document.createElement('h4');

    answerText.innerText = "Answer: " + numberTaskInfo.answer.toString();
    expectedAnswerText.innerText = "Expected Answer: " + numberTaskInfo.expected_answer.toString();

    card.appendChild(answerText);
    card.appendChild(expectedAnswerText);

    let cardBackwards = document.createElement('div');
    cardBackwards.className = "col-sm-6 col-lg-3";

    let backwardsAnswerText = document.createElement('h4');
    let backwardsExpectedAnswerText = document.createElement('h4');

    backwardsAnswerText.innerText = " Backwards Answer: " + numberTaskInfo.answer_backwards.toString();
    backwardsExpectedAnswerText.innerText = "Expected Backwards Answer: " + numberTaskInfo.expected_answer_backwards.reverse().toString();

    cardBackwards.appendChild(backwardsAnswerText);
    cardBackwards.appendChild(backwardsExpectedAnswerText);

    row.appendChild(card);
    row.appendChild(cardBackwards);
    column.appendChild(row);


    createScoreSection(column,numberTaskInfo.score);


    testCardBody.appendChild(column);

}

function createLettersCard(testCardBody, lettersTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let row = document.createElement('div');
    row.className = "row testPadding";
    let card = document.createElement('div');
    card.className = "column pl-75";

    let answerText = document.createElement('h4');
    let expectedAnswerText = document.createElement('h4');
    let occurrences = document.createElement('h4');

    answerText.innerText = "Answer: " + lettersTaskInfo.answer.toString();
    expectedAnswerText.innerText = "Given: " + lettersTaskInfo.letters.toString();
    occurrences.innerText = "Ocurrences of "+ lettersTaskInfo.target_letter +": " + lettersTaskInfo.occurrences;

    card.appendChild(expectedAnswerText);
    card.appendChild(answerText);
    card.appendChild(occurrences);


    row.appendChild(card);
    column.appendChild(row);


    createScoreSection(column,lettersTaskInfo.score);


    testCardBody.appendChild(column);

}

function createSubtractionCard(testCardBody, subtractionTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);

    let card = document.createElement('div');
    card.className = "column pl-75";


    let row = document.createElement('div');
    row.className = "row testPadding";

    let answerText = document.createElement('h4');

    answerText.innerText = "Answer: " + subtractionTaskInfo.answer.toString();

    card.appendChild(answerText);
    row.appendChild(card);
    column.appendChild(row);


    createScoreSection(column,subtractionTaskInfo.score);


    testCardBody.appendChild(column);

}

function createLanguageCard(testCardBody, languageTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let row = document.createElement('div');
    row.className = "row testPadding";


    for(let i = 0; i < languageTaskInfo.answer.length; ++i){
        let card = document.createElement('div');
        card.className = "col-sm-6 col-lg-3";
        let answerText = document.createElement('h4');
        let expectedAnswerText = document.createElement('h4');

        answerText.innerText = "Answer: " + decodeURI(languageTaskInfo.answer[i]);
        expectedAnswerText.innerText = "Expected Answer: " + decodeURI(languageTaskInfo.expected_answer[i]);

        card.appendChild(answerText);
        card.appendChild(expectedAnswerText);
        row.appendChild(card);
    }

    column.appendChild(row);


    createScoreSection(column,languageTaskInfo.score);


    testCardBody.appendChild(column);
}

function createRecallCard(testCardBody, recallTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let row = document.createElement('div');
    row.className = "row testPadding";
    let card = document.createElement('div');
    card.className = "col-sm-6 col-lg-3";

    let answerText = document.createElement('h4');
    let expectedAnswerText = document.createElement('h4');

    answerText.innerText = "Answer: " + recallTaskInfo.answer.toString();
    expectedAnswerText.innerText = "Expected Answer: " + recallTaskInfo.expected_answer.toString();

    card.appendChild(answerText);
    card.appendChild(expectedAnswerText);


    row.appendChild(card);
    column.appendChild(row);


    createScoreSection(column,recallTaskInfo.score);


    testCardBody.appendChild(column);

}

function createOrientationCard(testCardBody, orientationTaskInfo, taskTitle) {

    let column = document.createElement('div');
    column.className = "column";

    createTitleSection(column,taskTitle);


    let row = document.createElement('div');
    row.className = "row testPadding";


    for(let i = 0; i < orientationTaskInfo.answer.length; ++i){
        let card = document.createElement('div');
        card.className = "col-sm-6 col-lg-3";
        let answerText = document.createElement('h4');
        let expectedAnswerText = document.createElement('h4');

        answerText.innerText = "Answer: " + orientationTaskInfo.answer[i];
        expectedAnswerText.innerText = "Question: " + orientationTaskInfo.questions[i];

        card.appendChild(expectedAnswerText);
        card.appendChild(answerText);
        row.appendChild(card);
    }

    column.appendChild(row);


    createScoreSection(column,orientationTaskInfo.score);


    testCardBody.appendChild(column);
}



function traverse(data) {

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

    let TASKSTYPES = new Map();

    TASKSTYPES.set(GRAPH,"Alternating Trail Making");
    TASKSTYPES.set(CUBE,"Visuoconstructional Skills (Cube)");
    TASKSTYPES.set(WATCH,"Visuoconstructional Skills (Clock)");
    TASKSTYPES.set(IMAGE,"Naming");
    TASKSTYPES.set(MEMORY,"Memory");
    TASKSTYPES.set(ATTENTION_NUMBERS,"Digit Span");
    TASKSTYPES.set(ATTENTION_LETTERS,"Vigilance");
    TASKSTYPES.set(ATTENTION_SUBTRACTION,"Serial subtraction");
    TASKSTYPES.set(LANGUAGE,"Sentence repetition");
    TASKSTYPES.set(FLUENCY,"Verbal fluency");
    TASKSTYPES.set(ABSTRACTION,"Abstraction");
    TASKSTYPES.set(RECALL,"Delayed recall");
    TASKSTYPES.set(ORIENTATION,"Orientation");





    for (let index in data) {
        if (!!data[index] && typeof(data[index])=="object") {
            console.log("ARRAY", index, data[index]);

            if(index === "task_"+taskNumber){

                let title = TASKSTYPES.get(data[index].task_type);
                switch(data[index].task_type){
                    case GRAPH:
                        createTabForGraph(testCardBody,data[index], title);
                        break;
                    case CUBE:
                        createTabForDrawing(testCardBody,data[index],title);
                        break;
                    case WATCH:
                        createTabForDrawing(testCardBody,data[index],title);
                        break;
                    case IMAGE:
                        createImagesAnswer(testCardBody,data[index],title);
                        break;
                    case MEMORY:
                        createMemoryTab(testCardBody,data[index],title);
                        break;
                    case ATTENTION_NUMBERS:
                        createNumbersCard(testCardBody,data[index],title);
                        break;
                    case ATTENTION_LETTERS:
                        createLettersCard(testCardBody,data[index],title);
                        break;
                    case ATTENTION_SUBTRACTION:
                        createSubtractionCard(testCardBody,data[index],title);
                        break;
                    case LANGUAGE:
                        createLanguageCard(testCardBody,data[index],title);
                        break;
                    case FLUENCY:
                        createSubtractionCard(testCardBody,data[index],title);
                        break;
                    case ABSTRACTION:
                        createLanguageCard(testCardBody,data[index],title);
                        break;
                    case RECALL:
                        createRecallCard(testCardBody,data[index],title);
                        break;
                    case ORIENTATION:
                        createOrientationCard(testCardBody,data[index],title);
                        break;
                }

                ++taskNumber;
            }
            else{
                traverse(data[index]);
            }




        }
    }


}




