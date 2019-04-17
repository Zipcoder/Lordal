<template>
    <div class="container">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" type="text" v-model="assessment.name" name="name">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="url">Url</label>
                <input class="form-control" type="text" v-model="assessment.url" name="url">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="level">Assignment Type</label>
                <select class="form-control" type="text" v-model="assessment.level" name="level">
                    <option>Lab</option>
                    <option>Quiz</option>
                    <option>Exam</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="assigned_date">Assigned Date</label>
                <date-picker :config="options" class="form-control" v-model="assessment.assigned_date" name="assigned_date"></date-picker>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="due_date">Due Date</label>
                <date-picker :config="options" class="form-control" v-model="assessment.due_date" name="due_date"></date-picker>
            </div>
            <div class="form-group col-md-6 col">
                <p class="form-label">Gradeable?</p>
                <label class="switch" for="gradable" v-on:click="toggleGradable">
                    <input type="checkbox" v-model="assessment.gradable" name="gradable">
                    <span class="slider"></span>
                </label>
                <span v-if="assessment.gradable">Yes</span>
                <span v-else>No</span>
            </div>
            <div class="form-group col-md-6 col">
                <p class="form-label">Autograde?</p>
                <label class="switch" for="autograde" v-on:click="toggleAutograde">
                    <input type="checkbox" v-model="assessment.autograde" name="autograde" checked>
                    <span class="slider"></span>
                </label>
                <span v-if="assessment.autograde">Yes</span>
                <span v-else>No</span>
            </div>
            <div class="form-group col-md-6" v-if="assessment.gradable">
                <label class="form-label" for="max_score">Max Score</label>
                <input class="form-control" type="number" v-model="assessment.max_score" name="max_score">
                <label class="form-label" for="passing_score">Passing Score</label>
                <input class="form-control" type="number" v-model="assessment.passing_score" name="passing_score">
            </div>
            <div class="col-12">
                <button v-on:click="createAssessment" class="btn btn-success">Create Assessment</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                assessment: {
                      name: ""
                    , url: ""
                    , level: ""
                    , gradable: false
                    , autograde: false
                    , max_score: null
                    , assigned_date: null
                    , due_date: ""

                },
                options: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }       
            }
        },
        methods: {
            createAssessment: function() {
                window.axios.post("/api/assessments", this.assessment)
                    .then(function(results) {
                        window.location.replace("/staff/assessments");
                        window.toastr.success("Assessment Created")
                    });
            },
            toggleAutograde: function() {
                this.assessment.autograde = ! this.assessment.autograde;
            },
            toggleGradable: function() {
                this.assessment.gradable = ! this.assessment.gradable;
            }
        }
    };
</script>

<style type="text/css">
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.form-control + .bootstrap-datetimepicker-widget * {
  background-color: #F0F0F0;
  color: #0F0F0F;
}

</style>
