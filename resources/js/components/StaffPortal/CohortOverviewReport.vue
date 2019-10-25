<style>
    .green {
        color: green;
    }
    .yellow {
        color: yellow;
        background-color: black;
    }
    .red {
        color: red;
    }
    div#listing {
        float: left;
    }
    ol#students-list li a.selected {
        background-color: yellow;
    }
</style>
<template>
<div id="content">
<div id="listing">
    <h3>Students by Outstanding Labs Assigned/Completed</h3>
    <div id="students-breakdown">
        <ol id="students-list">
        </ol>
    </div>
    <h3>Lab Completion Rates</h3>
    <div id="labs-breakdown">
        <ol id="labs-list">
        </ol>
    </div>
</div>

<div id="charts">
    <a name="charts"><h3>Analytic Breakdown</h3></a>
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
</div>
</template>
<script>
</script>