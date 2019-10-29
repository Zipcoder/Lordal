console.log("self.students");

    export default {
        data: function() {
            return {
                students: [],
                currentSortDir: 'asc',
                currentSort: 'id'
            };
        },
        props: [],
        mounted() {
            var self = this;
            window.axios.get(`/api/students`)
                .then(function(response) {
                    self.students = response.data;
                    console.log(self.students);

        //console.log(JSON.stringify(feedProcessed));

                    //var results = self.students.assessments;
                    
                });
        },
        methods: {
        },
        computed: {

        }
    };
