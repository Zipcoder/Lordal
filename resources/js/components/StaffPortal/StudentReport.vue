<template>
    <div class="container">
        <div id="charts">
            <h3>Analytic Breakdown</h3>
            <div class="performance-chart">
                <canvas id="quiz_chart" width="100" height="100"></canvas>
            </div>
            <div class="performance-chart">
                <canvas id="exam_chart" width="100" height="100"></canvas>
            </div>
            <div class="performance-chart">
                <canvas id="lab_percentage_chart" width="100" height="100"></canvas>
            </div>
        </div>
        <div id="content">
            <h3>Labs Assigned/Completed</h3>
            <div class="lab submission" v-for="assessment in assessments.assessments">
                <div v-if="assessment.pivot.submission == undefined && assessment.level=='Lab'" class="awaiting">
                    {{assessment.name}}
                    : awaiting submission (Due {{assessment.due_date}})</div>
                <div class="submitted" v-else-if="assessment.level=='Lab'">
                    {{assessment.name}}
                    : <a v-bind:href="assessment.pivot.submission.submission_url" target="_blank" >{{assessment.pivot.submission.submission_url}}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                assessments: [],
                currentSortDir: 'asc',
                currentSort: 'id'
            };
        },
        props: ['student_id'],
        mounted() {
            var self = this;
            window.axios.get(`/api/students/${self.student_id}/assessments`)
                .then(function(response) {
                    self.assessments = response.data;


                    var results = self.assessments.assessments;
                    /*
                      var results = self.assessments.assessments.filter(function(item) {
                        return item.type == 'LAB';
                    });
                    * */

                    var text_output = "";
                    var total_labs=0;
                    var completed_labs = 0;
                    var quiz_scores = new Array();
                    var max_scores = new Array();
                    var quiz_passing_scores = new Array();
                    var quiz_names = new Array();
                    var exam_scores = new Array();
                    var exam_passing_scores = new Array();
                    var exam_max_scores = new Array();
                    var exam_names = new Array();
                    for(var i = 0 ; i < results.length; i++) {
                        if ( results[i].level == 'Lab' ) {
                            total_labs++;

                            try {
                                if (typeof results[i].pivot.submission.submission_url != "undefined") {
                                    completed_labs++;
                                }

                            } catch (e) {

                            }
                        }
                        else if (results[i].level=='Quiz' && results[i].gradable) {
                            var max_score = results[i].max_score;

                            var actual_score = 0;
                            try {
                                if (typeof results[i].pivot.submission.grade != "undefined") {
                                    actual_score =  (results[i].pivot.submission.grade) ? results[i].pivot.submission.grade : 0;
                                }

                            } catch (e) {

                            }
                            quiz_names.push(results[i].name);
                            quiz_scores.push(actual_score);
                            quiz_passing_scores.push(results[i].passing_score);
                            max_scores.push(max_score);
                        }
                        else if (results[i].level=='Exam' && results[i].gradable) {
                            var max_score = results[i].max_score;

                            var actual_score = 0;
                            try {
                                if (typeof results[i].pivot.submission.grade != "undefined") {
                                    actual_score =  (results[i].pivot.submission.grade) ? results[i].pivot.submission.grade : 0;
                                }

                            } catch (e) {

                            }
                            exam_names.push(results[i].name);
                            exam_scores.push(actual_score);
                            exam_passing_scores.push(results[i].passing_score);
                            exam_max_scores.push(max_score);
                        }

                    }

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
                                label: 'Passing Score',
                                fill: false,
                                backgroundColor: window.chartColors.green,
                                borderColor: window.chartColors.green,
                                data: quiz_passing_scores,
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

//
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
                                label: 'Passing Score',
                                fill: false,
                                backgroundColor: window.chartColors.green,
                                borderColor: window.chartColors.green,
                                data: exam_passing_scores,
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

                    window.onload = function() {
                        var ctx = document.getElementById('quiz_chart').getContext('2d');
                        var ctx2 = document.getElementById('exam_chart').getContext('2d');

                        window.myLine = new Chart(ctx, quiz_chart_config);
                        window.myLine2 = new Chart(ctx2, exam_chart_config);
                    };




                    var remaining_labs = total_labs - completed_labs;

                    var ctx = document.getElementById('lab_percentage_chart').getContext('2d');
                    var myChart = new Chart(ctx, {
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
                                text: 'Lab Completion'
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
                    });


                });
        },
        methods: {
            sort(s) {
                if(s === this.currentSort) {
                  this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                } else {
                    this.currentSort = s;
                    this.currentSortDir==='asc';
                }
            }
        },
        computed: {
            sortAssessments() {
                var self = this;
                return this.assessments.sort(function(a, b) {
                    let modifier = 1;
                    if(self.currentSortDir === 'desc') modifier = -1;
                    if(a[self.currentSort] < b[self.currentSort]) return -1 * modifier;
                    if(a[self.currentSort] > b[self.currentSort]) return 1 * modifier;
                    return 0;
                });
            }

        }
    };

</script>

<style type="text/css" scoped>
    .container div#content {
        width: 95%;
        margin: 0px auto;
    }
    div#charts {
        width: 95%;
        margin: 0px auto;
    }
    .lab.submission{
        margin: 4px 0px;
        color: #000;
        font-weight: bold;
    }
    .lab.submission a {
        color: #0000ff;
    }
    .lab div {
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 14px;
    }
    .lab div.submitted {
        background-color: lightgreen;
        margin-bottom: 2px;
    }
    .lab div.awaiting {
        background-color: lightpink;
        margin-bottom: 2px;
    }
    .performance-chart{
        width: 30%;
        height: auto;
        float: left;
        border: 1px solid #ccc;
        padding: 5px;
        margin: 1%;
        border-radius: 5px;
        background-color: #fff;
    }
    @media screen and (min-width: 980px) /* Desktop */ {
        .performance-chart {
            width: 30%;
            height: auto;
            margin: 1%;
        }
    }

    @media screen  and (max-width: 979px) /* Tablet */ {
        .lab {
            font-size: 12px;
        }
        h3 {
            text-align: center;
        }
        .performance-chart {
            width: 90%;
            height: auto;
            margin: 15px 8%;
        }
    }

    @media screen and (max-width: 500px) /* Mobile */ {
        .lab {
            font-size: 12px;
        }
        h3 {
            text-align: center;
        }
        .performance-chart {
            width: 90%;
            height: auto;
            margin: 0 8%;
        }
    }
</style>