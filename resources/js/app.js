/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

Vue.use(datePicker);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

/**
 * Passport Vues
 */
Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

// Components for the staff portal
Vue.component('students', require('./components/StaffPortal/StudentsComponent.vue'));
Vue.component('view-student', require('./components/StaffPortal/Students/StudentComponent.vue'));
Vue.component('student-assessment-row', require('./components/StaffPortal/Students/StudentAssessmentRow.vue'));
Vue.component('assessment-form', require('./components/StaffPortal/SingleComponents/AssessmentForm.vue'));
Vue.component('comments', require('./components/StaffPortal/SingleComponents/CommentsComponent.vue'));
Vue.component('submissions', require('./components/StaffPortal/SingleComponents/SubmissionsComponent.vue'));
Vue.component('submission-row', require('./components/StaffPortal/SingleComponents/SubmissionRow.vue'));
Vue.component('view-assessment', require('./components/StaffPortal/SingleComponents/AssessmentComponent.vue'));
Vue.component('labs', require('./components/StaffPortal/LabsComponent.vue'));
Vue.component('exams', require('./components/StaffPortal/ExamsComponent.vue'));
Vue.component('quizzes', require('./components/StaffPortal/QuizzesComponent.vue'));
Vue.component('student-report', require('./components/StaffPortal/StudentReport.vue'));

// Components for the student portal
Vue.component('student-profile', require('./components/StudentPortal/StudentPortal.vue'));
Vue.component('student-assessments', require('./components/StudentPortal/StudentAssessmentsComponent.vue'));
Vue.component('unsubmitted-labs', require('./components/StudentPortal/UnsubmittedLabs.vue'));
Vue.component('assessment-row', require('./components/StudentPortal/StudentAssessmentRow.vue'));
Vue.component('submission-form', require('./components/StudentPortal/SubmissionForm.vue'));

// mixins to pass to all components. These can be accessed through component.$root.[method name]
const mixins = {
    methods: {
        flattenObject: function(ob) {
            var toReturn = {};
            
            for (var i in ob) {
                if (!ob.hasOwnProperty(i)) continue;
                
                if ((typeof ob[i]) == 'object') {
                    var flatObject = this.flattenObject(ob[i]);
                    for (var x in flatObject) {
                        if (!flatObject.hasOwnProperty(x)) continue;
                        
                        toReturn[i + '.' + x] = flatObject[x];
                    }
                } else {
                    toReturn[i] = ob[i];
                }
            }
            return toReturn;
        },
        comparator: function sortObj(key) {
            return function compare(a, b) {
                a = a[key];
                b = b[key];
                
                if(a == undefined) {
                    a = 'null';
                }
                if(b == undefined) {
                    b = 'null';
                }
                var type = (typeof(a) === 'string' ||
                            typeof(b) === 'string') ? 'string' : 'number';



                var result;
                if (type === 'string') result = a.localeCompare(b);
                else result = a - b;
                return result;
            }
        }

    }
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    mixins: [mixins],
    el: '#app'
});
