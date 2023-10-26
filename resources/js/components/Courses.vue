<template>
        <v-row class="my-3">


            <v-col xs="6" sm="6" md="3" v-for="(course, i) in courses" :key="i">
                <v-card @click="goToCourseView(course.id)">
                    <v-img
                    :aspect-ratio="16/9"
                    :src="CourseImagePath(course)"></v-img>

                    <v-card-title>
                        {{ course.course_title }}
                    </v-card-title>

                    <v-card-subtitle>
                        <i class="fa fa-chalkboard-teacher my_icon"></i>
                        Created by {{ instructor ? instructorName(instructor) : instructorName(course.instructor) }}

                    </v-card-subtitle>

                    <v-divider class="mx-4"></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text>{{ parseFloat(course.price) ? course.price + ' $' : 'Free' }}</v-btn>
                    </v-card-actions>

                </v-card>
            </v-col>
        </v-row>

</template>

<script>
import { mapGetters, mapActions } from "vuex";



export default {
    props: ['courses', 'instructor', 'goTo'],
    data: () => ({
    }),

    created() {
    },

    computed: {
        ...mapGetters(['CourseImagePath']),
    },

    methods: {
        // ...mapActions(["GetLastestCourses"]),

        goToCourseView(course_id) {
            let route = ''
            if(this.goTo){
                route = this.goTo + course_id
            }
            else{
                route = '/course-view/' + course_id
            }
            this.$router.push(route)
        },

        instructorName(instructor){
            return instructor.first_name + ' ' + instructor.last_name
        }



    }

};
</script>
<style scoped>
.v-text-field--outlined >>> fieldset {
  border-color: #1976d2;
}
</style>
