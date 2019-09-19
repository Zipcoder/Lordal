<template>
    <div class="container mb-5">
        <div class="form row">
            <div class="form-group col-md-6">
                <label for="github_username">Name</label>
                <input class="form-control" type="text" name="github_username" v-model="assessment.name">
            </div>

            <div class="form-group col-md-6">
                <label for="url">Url</label>
                <input class="form-control" type="text" name="url" v-model="assessment.url">
            </div>
            <div class="form-group col-md-6">
                <label for="level">Level</label>
                <input class="form-control" type="text" name="level" v-model="assessment.level">
            </div>
            <div class="form-group col-md-6">
                <label for="gradable">Gradable</label>
                <input class="form-control" type="checkbox" name="gradable" v-model="assessment.gradable">
            </div>
            <div class="form-group col-md-6">
                <label for="max_score">Max Score</label>
                <input class="form-control" type="number" name="max_score" v-model="assessment.max_score">
            </div>
            <div class="form-group col-md-6">
                <label for="assigned_date">Assigned Date</label>
                <date-picker :config="options" class="form-control" type="datetime" name="assigned_date" v-model="assessment.assigned_date"></date-picker>
            </div>
            <div class="form-group col-md-6">
                <label for="due_date">Due Date</label>
                <date-picker :config="options" class="form-control" type="datetime" name="due_date" v-model="assessment.due_date"></date-picker>
            </div>
        </div>

        <button class="btn btn-success" v-on:click="updateAssessment()">Update Assessment</button>

        <submissions v-bind:assessment_id="assessment_id" :gradable="assessment.gradable"></submissions>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                assessment: {},
                options: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }       
            }
        },
        props: ['assessment_id'],
        mounted() {
            var self=this;
            window.axios.get(`/api/assessments/${self.assessment_id}`)
                .then(function(results) {
                    self.assessment = results.data;
                })
        },
        methods: {
            updateAssessment: function(){
                var self = this;
                window.axios.put(`/api/assessments/${self.assessment.id}`, self.assessment)
                    .then(function(response) {
                        self.$emit('assessment-changed', self.assessment)
                        window.toastr.success("Assessment Updated")
                    });
            }
        }
    };
</script>
