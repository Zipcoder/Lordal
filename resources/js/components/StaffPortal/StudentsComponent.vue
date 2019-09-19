<template>
    <div class="container">
        <div class=" row justify-content-center">
            <table id="studentDataTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th @click="sort('id')">#</th>
                        <th @click="sort('user.name')">Name</th>
                        <th @click="sort('github_username')">Github</th>
                        <th @click="sort('user.email')">Email</th>
                        <th @click="sort('user.dismissed')">Dismissed?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="student in sortStudents">
                      <td><a :href="'/staff/students/' + student.id">{{student["id"]}}</a></td>
                      <td><a :href="'/staff/students/' + student.id">{{student["user.name"]}}</a></td>
                      <td><a target="_blank" :href="'https://github.com/' + student['github_username']">{{student["github_username"]}}</a></td>
                      <td><a :href="'/staff/students/' + student.id">{{student["user.email"]}}</a></td>
                      <td><a :href="'/staff/students/' + student.id">{{student["dismissed"] == 1 ? "Yes" : "No"}}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                students: [],
                currentSortDir: 'asc',
                currentSort: 'id'
            };
        },
        mounted() {
            var self = this;
            window.axios.get('/api/students')
                .then(function(response) {
                    response.data.forEach((student) => {
                        if (student.dont_track != true){
                            self.students.push((self.$root.flattenObject(student)));
                        }
                    });
                    self.activeStudent = self.students[0];
                });
        },
        methods: {
            sort(s) {
                if(s === this.currentSort) {
                  this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                } else {
                    this.currentSort = s;
                    this.currentSortDir = 'asc';
                }
            },
        },
        computed: {
            sortStudents() {
                return this.students.sort((a, b) => {
                    let modifier = 1;
                    if(this.currentSortDir === 'desc') modifier = -1;
                    if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                    if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                    return 0;
                });
            }
        }
    }
</script>