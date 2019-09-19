<template>
    <div class="container p-0">
        <div class="pt-5">
            <button class="btn btn-warning" v-on:click="assignAssessment()">Assign</button>
        </div>

        <div class="row justify-content-center mt-5">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th @click="sort('user.name')">Student Name</th>
                        <th @click="sort('pivot.submission.submission_url')">Submission Url</th>
                        <th @click="sort('pivot.submission.latest_hash')">Latest Hash</th>
                        <th @click="sort('pivot.submission.latest_hash')">Submitted</th>
                        <th @click="sort('pivot.submission.grade')">Grade</th>
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <submission-row v-for="submission in sortSubmissions" :submission="submission" :key="submission.id" :gradable="gradable"></submission-row>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                submissions: [],
                currentSortDir: 'asc',
                currentSort: 'id'
            }
        },
        props: ['assessment_id', 'gradable'],
        mounted() {
            var self = this;
            var flattenObject = self.$root.flattenObject;
            console.log(`/api/assessments/${self.assessment_id}/students`);
            window.axios.get(`/api/assessments/${self.assessment_id}/students`)
                .then(function(results) {
                    results.data.students.forEach(function(student) {
                        if(student["dont_track"] != true) {
                            var flatStudent = flattenObject(student);
                            flatStudent['pivot.submission.submission_url'] = flatStudent['pivot.submission.submission_url'] || "";
                            flatStudent['pivot.submission.latest_hash'] = flatStudent['pivot.submission.latest_hash'] || "";
                            flatStudent['pivot.submission.grade'] = flatStudent['pivot.submission.grade'] || 0;
                            self.submissions.push(flatStudent);
                        }
                    });
                });
        },
        methods: {
            assignAssessment: function() {
                self = this;
                window.axios.post(`/api/assessments/${self.assessment_id}/students`)
                    .then(function(response) {
                        window.axios.get(`/api/assessments/${self.assessment_id}/students`)
                            .then(function(results) {
                                self.submissions = results.data.students;
                                window.toastr.success("Assessment Assigned")
                        });
                    });
            },
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
            sortSubmissions() {
                var self = this;
                return this.submissions.sort(function(a, b) {
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