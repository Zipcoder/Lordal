
function reqListener () {
   // console.log(this.responseText);
}

function appendTo(string, idParent, nodeElement="li"){
    var node = document.getElementById(idParent);
    var liNode = document.createElement(nodeElement);
    liNode.innerHTML = string;
    node.appendChild(liNode);

}

function getCompClass(compRate){
    if (compRate > 75) {
        return "green";
    }
    else if(compRate > 50) {
        return "yellow";
    }
    else {
        return "red";
    }
}

function compareLabCompletion( a, b ) {
    if ( a.total_lab_completion_rate < b.total_lab_completion_rate ){
      return 1;
    }
    if ( a.total_lab_completion_rate > b.total_lab_completion_rate ){
      return -1;
    }
    return 0;
}

function compareAssessment( a, b ) {
    if ( a.id < b.id ){
      return -1;
    }
    if ( a.id > b.id ){
      return 1;
    }
    return 0;
}


function processStudent(studentData) {
    var total_labs=0;
    var completed_labs = 0;
    var quizzes = new Array();
    var exams = new Array();
    var labs = new Array();
    var quiz_scores = new Array();
    var quiz_max_scores = new Array();
    var quiz_names = new Array();
    var exam_scores = new Array();
    var exam_max_scores = new Array();
    var exam_names = new Array();

    if (studentData.assessments) {
        studentData.assessments.sort(compareAssessment);
        for(var i = 0 ; i < studentData.assessments.length; i++) {
                if ( studentData.assessments[i].level == 'Lab' ) {
                total_labs++;
                try {
                    if (typeof studentData.assessments[i].pivot.submission.submission_url != "undefined") {
                        completed_labs++;
                    }

                } catch (e) {

                }
            }
            else if (studentData.assessments[i].level=='Quiz' && studentData.assessments[i].gradable) {
                quiz_max_score = studentData.assessments[i].max_score;

                varactual_score = 0;
                try {
                    if (typeof studentData.assessments[i].pivot.submission.grade != "undefined") {
                        actual_score =  (studentData.assessments[i].pivot.submission.grade) ? studentData.assessments[i].pivot.submission.grade : 0;
                    }

                } catch (e) {

                }
                quiz_names.push(studentData.assessments[i].name);
                quiz_scores.push(actual_score);
                quiz_max_scores.push(quiz_max_score);
                var quiz_result = new Object();
                quiz_result.id = studentData.assessments[i].id;
                quiz_result.name = studentData.assessments[i].name;
                quiz_result.score = actual_score;
                quiz_result.max_score = quiz_max_score;
                //quiz_result.passing_score = var;
                quizzes.push(quiz_result);
            }
            else if (studentData.assessments[i].level=='Exam' && studentData.assessments[i].gradable) {
                max_score = studentData.assessments[i].max_score;

                actual_score = 0;
                try {
                    if (typeof studentData.assessments[i].pivot.submission.grade != "undefined") {
                        actual_score =  (studentData.assessments[i].pivot.submission.grade) ? studentData.assessments[i].pivot.submission.grade : 0;
                    }

                } catch (e) {

                }
                exam_names.push(studentData.assessments[i].name);
                exam_scores.push(actual_score);
                exam_max_scores.push(max_score);
            }

        }
    }

    return buildProcessedStudent(quiz_names, quiz_scores, quiz_max_scores, exam_names, exam_scores, exam_max_scores, total_labs, completed_labs, studentData.user, studentData.id);
}

function buildProcessedStudent(quiz_names, quiz_scores, quiz_max_scores, exam_names, exam_scores, exam_max_scores, total_labs, completed_labs, user, student_id){
    studentProcessed = new Object();
    studentProcessed.quiz_names = quiz_names;
    studentProcessed.quiz_scores = quiz_scores;
    studentProcessed.quiz_max_scores = quiz_max_scores;
    studentProcessed.exam_names = exam_names;
    studentProcessed.exam_scores = exam_scores;
    studentProcessed.exam_max_scores = exam_max_scores;
    studentProcessed.total_labs = total_labs;
    studentProcessed.completed_labs = completed_labs;
    studentProcessed.user = user;
    studentProcessed.student_id = student_id;
    return studentProcessed;
}

function getQueryVariable(variable) {
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}

function processFeed(dataFeed) {
    var feedP = new Array();    
    for(var k = 0 ; k < dataFeed.students.length; k++) {
        feedP.push(processStudent(dataFeed.students[k]));
    }    
    return feedP;
}

function chartStudentLabs(remaining_labs, completed_labs, student_name){
    var lab_completiong_chart_config = {
        type: 'pie',
        data: {
            labels: ['Remaining Labs', 'Completed Labs'],
            datasets: [{
                label: '# of Votes',
                data: [remaining_labs, completed_labs],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: (student_name !=="") ? student_name + '\'s Lab Completion' : 'Lab Completion'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '(Completion shown as percentage)'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    };
    return lab_completiong_chart_config;
}

var lab_names = new Array();
var labs_submitted = new Array();
var lab_completion_rates = new Array();


function chartLabsAll(lab_names, labs_submitted,lab_completion_rates, cohortName){
    var lab_completiong_chart_config =  {
        type: 'line',
        data: {
            labels: lab_names,
            datasets: [ {
                label: 'Lab Completion Rate (%)',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: lab_completion_rates,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: (cohortName !=="") ? cohortName + '\'s Labs' : 'Labs'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Lab Name'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Score'
                    }
                }]
            }
        }
    };
    return lab_completiong_chart_config;
}


function chartStudentQuizzes(quiz_names, quiz_scores, max_scores, passing_scores, student_name){
    var quiz_chart_config = {
        type: 'line',
        data: {
            labels: quiz_names,
            datasets: [{
                label: 'Actual Score',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: quiz_scores,
                fill: false,
            }, {
                label: 'Max Score',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: max_scores,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: (student_name !=="") ? student_name + '\'s Quizzes' : 'Quizzes'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Quiz'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Score'
                    }
                }]
            }
        }
    };
    return quiz_chart_config;
}

function chartStudentAssessments(exam_names, exam_scores, exam_max_scores, exam_passing_scores, student_name){
    var exam_chart_config = {
        type: 'line',
        data: {
            labels: exam_names,
            datasets: [{
                label: 'Actual Score',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: exam_scores,
                fill: false,
            }, {
                label: 'Max Score',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: exam_max_scores,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: (student_name !=="") ? student_name + '\'s Exams' : 'Exams'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Exam'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Score'
                    }
                }]
            }
        }
    };
    return exam_chart_config;
}

function chartAssessmentsAll(exam_scores, exam_max_scores, exam_passing_scores){
    var exam_chart_config = {
        type: 'line',
        data: {
            labels: exam_names,
            datasets: [{
                label: 'Actual Score',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: exam_scores,
                fill: false,
            }, {
                label: 'Max Score',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: exam_max_scores,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Exams'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Exam'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Score'
                    }
                }]
            }
        }
    };
    return exam_chart_config;
}

function chartQuizzesAll(quiz_scores, max_scores, passing_scores) {

    //
    var quiz_chart_config = {
        type: 'line',
        data: {
            labels: quiz_names,
            datasets: [{
                label: 'Average Score',
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                data: quiz_scores,
                fill: false,
            }, {
                label: 'Passing Score',
                fill: false,
                backgroundColor: window.chartColors.green,
                borderColor: window.chartColors.green,
                data: passing_scores,
            }, {
                label: 'Max Score',
                fill: false,
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: max_scores,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Quizzes'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Quiz'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Score'
                    }
                }]
            }
        }
    };
    return quiz_chart_config;
}

function showStudentBreakdown(request) {


    var student_id = getQueryVariable('studentid');

    var data = JSON.parse(request.responseText);
    var labs_comp = {};
    var feedProcessed = processFeed(data);
        //console.log(JSON.stringify(feedProcessed));

    ///*
    for(var i = 0 ; i < data.students.length; i++) {
        var _total_labs=0;
        var _completed_labs = 0;

        for(var j = 0 ; j < data.students[i].assessments.length; j++) {            
            if(data.students[i].assessments[j].level == 'Lab') {
                _total_labs++;                    
                if (data.students[i].assessments[j].pivot.submission != null) {
                    _completed_labs++;
                    if((labs_comp[data.students[i].assessments[j].name]) != null) {
                        labs_comp[data.students[i].assessments[j].name]++;
                    }
                    else {
                        labs_comp[data.students[i].assessments[j].name] = 1;
                    }
                }
                else if((labs_comp[data.students[i].assessments[j].name]) == null) {
                    labs_comp[data.students[i].assessments[j].name] = 0;
                }
            }
            _completion_labs = (_total_labs > 0) ? Math.round((_completed_labs/_total_labs)*100, 2) : 0;
        }

        data.students[i].total_lab_completed = _completed_labs;
        data.students[i].total_lab_completion_rate = _completion_labs;
        data.students[i].total_lab_outstanding = (_total_labs- _completed_labs );
    }

    data.students.sort( compareLabCompletion );
    var chartAll = true;
    var comp_tot = 0;
    var avg_comp = 0;
    for(var i = 0 ; i < data.students.length; i++) {
        appendTo("\<strong\>\<a class=\"student-"+ data.students[i].id +"\" href\=\"/report.html?studentid="+ data.students[i].id +"\#charts\"\>" + data.students[i].user.name + " (" + data.students[i].github_username + ")\<\/a\>\<\/strong\> total labs completed: "+ data.students[i].total_lab_completed+ " |  total labs outstanding: "+ data.students[i].total_lab_outstanding+ " | \<span class=\"" + getCompClass(data.students[i].total_lab_completion_rate) +"\"\>("+ data.students[i].total_lab_completion_rate + "\% complete)\<\/span\>", "students-list");
        comp_tot += data.students[i].total_lab_completion_rate;
        if (student_id && data.students[i].id == student_id) {
            chartAll = false;
            var stuP = processStudent(data.students[i]);
//            console.log(JSON.stringify(stuP));
            var student_id_class_string = 'student-' + student_id;
            var chk = document.getElementsByClassName(student_id_class_string);
            chk.className += " selected";
            stuP.remaining_labs = stuP.total_labs - stuP.completed_labs;
            


            var exam_chart_config = chartStudentAssessments(stuP.exam_names, stuP.exam_scores, stuP.exam_max_scores, stuP.exam_passing_scores, data.students[i].user.name);
            var quiz_chart_config = chartStudentQuizzes(stuP.quiz_names, stuP.quiz_scores, stuP.quiz_max_scores, stuP.quiz_passing_scores, data.students[i].user.name);
            var lab_completion_chart_config = chartStudentLabs(stuP.remaining_labs, stuP.completed_labs, data.students[i].user.name);

            var ctx = document.getElementById('quiz_chart').getContext('2d');
            var ctx2 = document.getElementById('exam_chart').getContext('2d');
            var ctx3 = document.getElementById('lab_percentage_chart').getContext('2d');

            window.myLine = new Chart(ctx, quiz_chart_config);
            window.myLine2 = new Chart(ctx2, exam_chart_config);
            var myChart = new Chart(ctx3, lab_completion_chart_config);
        }
        

    }

    
    avg_comp = Math.round(comp_tot/data.students.length);

    appendTo("Average completion: \<span class=\"" + getCompClass(avg_comp) +"\"\>"+ avg_comp + "%\<\/span\>", "students-breakdown", "p");

    lab_keys = Object.keys(labs_comp);
    var sortable = new Array();
    for(var i = 0 ; i < lab_keys.length; i++) {
        var lab_this = new Object();
        lab_this.name = lab_keys[i];
        lab_this.submitted = labs_comp[lab_keys[i]];
        lab_this.total_lab_completion_rate = (labs_comp[lab_keys[i]] != 0) ? Math.round(labs_comp[lab_keys[i]]*100/data.students.length) : 0;
        sortable[i] = lab_this;
    }

    var labs_all = sortable;
    sortable.sort( compareLabCompletion );

    for(var i = 0 ; i < sortable.length; i++) {
        appendTo(sortable[i].name + ": \<span class=\""+ getCompClass(sortable[i].total_lab_completion_rate) +"\"\>"+ sortable[i].total_lab_completion_rate + "% submitted\<\/span\>", "labs-list" );
    }

    /**/
    if (chartAll) {
        //chart all quizzes
        var quiz_names = new Array();
        var quizzes_scores = new Array();
        var quizzes_max_scores = new Array();
        var quizzes_passing_scores = new Array();
        //chart all exams
        var exam_names = new Array();
        var exam_scores = new Array();
        var exam_max_scores = new Array();
        var exam_passing_scores = new Array();
        //chart all labs
        var lab_names = new Array();
        var labs_submitted = new Array();
        var lab_completion_rates = new Array();
        
    //console.log(JSON.stringify(labs_all));
    for(var j = 0 ; j < labs_all.length; j++) {
        lab_names.push(labs_all[j].name);
        labs_submitted.push(labs_all[j].submitted);
        lab_completion_rates.push(labs_all[j].total_lab_completion_rate);

    }
    //console.log(JSON.stringify(lab_names));
    var lab_all_config =  chartLabsAll(lab_names, labs_submitted, lab_completion_rates, "Cohort");


    //var exam_chart_config = chartStudentAssessments(exam_names, exam_scores, exam_max_scores, exam_passing_scores, , "Cohort");
    //var quiz_chart_config = chartStudentQuizzes(quiz_names, quiz_scores, quiz_max_scores, quiz_passing_scores, , "Cohort");
    
    var ctx = document.getElementById('quiz_chart').getContext('2d');
    var ctx2 = document.getElementById('exam_chart').getContext('2d');
    var ctx3 = document.getElementById('lab_percentage_chart').getContext('2d');
/*
    window.myLine = new Chart(ctx, quiz_chart_config);
    window.myLine2 = new Chart(ctx2, exam_chart_config);*/
    var myChart = new Chart(ctx3, lab_all_config);
    }
    
}

var urlParams = new URLSearchParams(location.search);

var request = new XMLHttpRequest();

var urlParams = new URLSearchParams(location.search);

var request = new XMLHttpRequest();

request.open('GET', '/generate_json_report.php');
request.onreadystatechange = function () {
    if((request.status == 200) && (request.readyState==4)){
        showStudentBreakdown(request);
    }
}
request.send();
