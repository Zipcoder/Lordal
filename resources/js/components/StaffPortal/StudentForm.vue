<template>
    <div class="container">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label" for="name">Name</label>
                <input class="form-control" type="text" v-model="student.name" name="name" placeholder="name">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="github_username">Github Username</label>
                <input class="form-control" type="text" v-model="student.github_username" name="github_username" placeholder="username">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="cell_number">Cell Phone Number</label>
                <input class="form-control" type="text" v-model="student.cell_number" name="cell_number" placeholder="6105552987">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label" for="email">Email</label>
                <input class="form-control" type="text" v-model="student.email" name="email" placeholder="student@email.com">
            </div>
            <div class="col-12">
                <button v-on:click="createStudent" class="btn btn-success">Create Student</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                student: {
                    'cell_number':     ""
                  , 'github_username': ""
                  , 'name': ""
                  , 'email': ""
                }
            }
        },
        methods: {
            createStudent: function() {
                window.axios.post("/api/students", this.student)
                    .then(results => {
                        window.toastr.success("Student Created");
                        this.student.cell_number = "";
                        this.student.github_username = "";
                        this.student.name = "";
                        this.student.email = "";
                    });
            }
        }
    };
</script>