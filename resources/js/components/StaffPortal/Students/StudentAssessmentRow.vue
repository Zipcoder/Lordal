<template>
    <tr>
        <td>{{assessment.id}}</td>
        <td><a :href="assessment.url">{{assessment.url}}</a></td>
        <td><a :href="'/staff/assessment/2' + assessment.id">{{assessment.name}}</a></td>
        <td>{{assessment.level}}</td>
        <td>{{assessment.gradable ? "Yes" : "No"}}</td>
        <td>
            <a v-if="assessment['pivot.submission.submission_url']" v-bind:href="assessment['pivot.submission.submission_url']">Yes</a>
            <span v-else>No</span>
        </td>
        <td>
            <div v-if="!editingGrade && assessment.gradable && assessment['pivot.submission.submission_url']">
	        	{{assessment['pivot.submission.grade']}}
                <button v-on:click="showGradeForm" class="btn btn-info">Modify Grade</button>
            </div>
            <div v-if="editingGrade && assessment.gradable">
                <input type="number" name="grade" v-model="assessment['pivot.submission.grade']">
                <button class="btn btn-success" v-on:click="updateSubmission">Update</button>
            </div>

        </td>
        <td>{{assessment.max_score}}</td>
        <td>{{assessment.assigned_date}}</td>
        <td>{{assessment.due_date}}</td>
    </tr>
</template>

<script>
	export default {
		data: function() {
			return {
				editingGrade: false
			}
		},
		props: ['assessment'],
		methods: {
			showGradeForm: function() {
				this.editingGrade = true;
			},
			updateSubmission: function() {
                var self = this;
                window.axios.put(`/api/submissions/${self.assessment['pivot.submission.id']}`,
                    {grade: self.assessment['pivot.submission.grade']})
                    .then(function(result) {
                        self.editingGrade = false;
                        console.log(self.assessment['pivot.submission.id'])
                        console.log({grade: self.assessment['pivot.submission.grade']})
                        window.toastr.success("Grade updated")
                    })
			}
		}
	}
</script>