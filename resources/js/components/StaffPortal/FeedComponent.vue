<template>
    <div class="container">
        <h1> Here are the latest notes </h1>
        <div class="row justify-content-center">
            <div class="card col-md-10 col-sm-12 my-2" v-for="comment in comments">
                <div class="card-body">
                    <p class="card-head">{{comment.student.user.name}} <small><a :href="'/staff/students/' + comment.student.id ">Profile</a></small> </p>
                    <p class="card-text">{{comment.body}}</p>
                    <p class="card-footer">{{comment.user.name}} - {{comment.user.email}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                comments: []
            };
        },
        mounted() {
            var self = this;
            window.axios.get('/api/comments')
                .then(function(response) {
                    self.comments = response.data.reverse();
                });
        }
    };
</script>