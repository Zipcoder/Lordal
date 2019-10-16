<template>
    <div class="container mb-5">
        <div id="user_info" class="row mb-3">
            <div class="col-6"><span><span class="font-weight-bold"> Name: </span> {{student.user.name}}</span></div>
            <div class="col-6"><span><span class="font-weight-bold"> Email: </span>{{student.user.email}}</span></div>
            <div class="col-6"><span><span class="font-weight-bold"> Created_at: </span>{{student.user.created_at}}</span></div>
            <div class="col-6"><span><span class="font-weight-bold"> Updated_at: </span>{{student.user.updated_at}}</span></div>
        </div>

        <div class="form">
            <div class="form-group">
                <label for="github_username">Username</label>
                <input class="form-control" type="text" name="github_username" v-model="student.github_username">
            </div>
            <div class="form-group">
                <label for="cell_number">Phone Number</label>
                <input class="form-control" type="text" name="cell_number" v-model="student.cell_number">
            </div>
            <div class="form-group">
                <label for="section">Section</label>
                <input class="form-control" type="text" name="section" v-model="student.section">
            </div>
            <div class="form-group">
                <label for="github_id">Github ID</label>
                <input class="form-control" type="text" name="github_id" v-model="student.github_id">
            </div>
            <div class="form-group">
                <label for="serial_number">Macbook Serial Number</label>
                <input class="form-control" type="text" name="serial_number" v-model="student.serial_number">
            </div>


            <div class="row">            
                <div class="form-group col-sm-6">
                    <label for="dismissed">Dismissed?</label>
                    <input class="form-control" type="checkbox" name="dismissed" v-model="student.dismissed">
                </div>

                <div class="form-group col-sm-6">
                    <label for="dont_track">Don't Track</label>
                    <input class="form-control" type="checkbox" name="dont_track" v-model="student.dont_track">
                </div>
            </div>

            <button class="btn btn-success" v-on:click="updateStudent()">Update Student</button>
        </div>
        <h2 class="mt-5"><a href="/staff/students/{{self.student.id}}/performance">Performance Report</a></h1>

        <h1 class="mt-5">Assessments</h1>
        <div  id="student_assessments" class="justify-content-center mt-5 mb-5">
            <table class="table table-bordered table-striped">
                <thead class="bg-primary">
                    <tr>
                        <th @click="sort('id')">#</th>
                        <th @click="sort('url')">url</th>
                        <th @click="sort('name')">name</th>
                        <th @click="sort('level')">level</th>
                        <th @click="sort('gradable')">gradable</th>
                        <th @click="sort('submitted')">submitted</th>
                        <th @click="sort('pivot.submission.grade')">score</th>
                        <th @click="sort('max_score')">max_score</th>
                        <th @click="sort('assigned_date')">assigned_date</th>
                        <th @click="sort('due_date')">due_date</th>
                    </tr>
                </thead>
                <tbody>
                    <student-assessment-row v-for="assessment in sortAssessments" :assessment="assessment" :key="assessment.id"></student-assessment-row>
                </tbody>
            </table>
        </div>

        <h1 class="mt-5">Comments</h1>
        <comments v-bind:student_id="student.id"></comments>

    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                student: {id: this.student_id, user: {}},
                assessments: [],
                currentSortDir: 'asc',
                currentSort: 'id'
            }
        },
        props: ['student_id'],
        created() {
            self = this;

            window.axios.get(`/api/students/${self.student.id}/assessments`)
                .then(function(results) {
                    self.student = results.data;

                    results.data.assessments.forEach(function(assessment) {
                        let flatAssessment = self.$root.flattenObject(assessment);
                        flatAssessment.submitted = flatAssessment['pivot.submission.submission_url'] != null;
                        flatAssessment['pivot.submission.grade'] = flatAssessment['pivot.submission.grade'] ? parseInt(flatAssessment['pivot.submission.grade']) : 0
                        self.assessments.push(flatAssessment);
                    });
                })
        },
        methods: {
            updateStudent: function(){
                var self = this;
                window.axios.put(`/api/students/${self.student.id}`, self.student)
                    .then(function(response) {
                        self.$emit('student-changed', self.student);
                        window.toastr.success("Student updated");
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

<style scoped lang="scss">
    #student_assessments {
        max-height: 500px;
        overflow-y: scroll;
        thead {
            top: 0;
            th {
                top: -1px;
                z-index: 10;
            }
        }
    }
</style>