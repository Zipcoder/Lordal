
<template>
    <tr>
        <td scope="col"><a :href="'/staff/students/' + submission.id">{{submission['user.name']}}</a></td>
        <td scope="col"><a v-if="submission['pivot.submission.submission_url']" v-bind:href="submission['pivot.submission.submission_url']" target="_blank">{{submission['pivot.submission.submission_url']}}</a></td>
        <td scope="col">{{submission['pivot.submission.latest_hash']}}</td>
        <td scope="col">{{submission['pivot.submission.submission_url'] ? "Yes" : "No"}}</td>
        <td scope="col">{{submission['pivot.submission.grade']}}</td>
        <td scope="col">
            <div v-if="!editing && submission['pivot.submission.submission_url']">
                <button @click="showGradeForm" class="btn btn-info">Modify</button>
            </div>
            <div v-if="editing && submission['pivot.submission.submission_url']">
                <input type="number" name="grade" v-model="submission['pivot.submission.grade']">
                <button class="btn btn-success" v-on:click="updateSubmission">Update</button>
            </div>
            <div v-if="gradable && submission['pivot.submission.submission_url']" class="my-2">
                <button class="btn btn-warning" @click="grade">Regrade</button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        data: function() {
            return {
                editing: false
            }
        },
        props: ['submission', 'gradable'],
        methods: {
            showGradeForm: function() {
                this.editing = true;
            },
            updateSubmission: function() {
                var self = this;
                window.axios.put(`/api/submissions/${self.submission['pivot.submission.id']}`, {grade: self.submission['pivot.submission.grade']})
                    .then(function(result) {
                        self.editing = false;
                        window.toastr.success("Grade updated")
                    })
            },
            grade: function() {
                var self = this;
                window.axios.post(`/api/grader/${self.submission['pivot.id']}`)
                    .then(function(result) {
                        self.editing = false;
                        window.toastr.success("Submission sent to be Graded")
                    })
            },
        }
    }
</script>