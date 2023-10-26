<template>
    <v-container>


        <v-row class="my-3">


            <v-col class="text-center" cols="12">
                <h1 class="primary--text">Learn Courses Online</h1>
            </v-col>
            <v-col class="text-center d-flex align-center justify-center" cols="12">
                <v-text-field append-icon="mdi-magnify" @click:append="searchCourse()" outlined style="max-width: 400px;"
                    v-model="search" placeholder="Search Course Title" single-line></v-text-field>

                <!-- <v-btn icon @click="searchCourse()">
                    <v-icon>mdi-magnify</v-icon>
                </v-btn> -->


            </v-col>

            <v-col cols="12">
                <!-- <v-divider></v-divider> -->
                <h3>Lastest Courses</h3>
            </v-col>
        </v-row>


        <Courses :courses="LastestCourses"></Courses>

        <v-divider></v-divider>
        <v-row class="my-3">

            <v-col cols="6">
                <h1 class="my-3">Learn every topic, everytime.</h1>
                <div>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sunt, corrupti nam aut magnam doloremque
                    debitis ab consectetur, architecto labore nisi accusamus sapiente odio numquam tempora. Excepturi
                    voluptatum veniam porro sequi.
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque culpa dolores at quo, nihil rem aut quos
                    voluptates aspernatur nesciunt, ab nam blanditiis harum illo hic placeat reiciendis ipsa optio!
                </div>
                <v-btn class="my-8" tile color="success" to="/courses">Explore Courses</v-btn>
            </v-col>
            <v-col cols="6">
                <img style="width: 100%;" src="/imgs/logo.jpg" alt="">
            </v-col>
        </v-row>


    </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import Courses from '../components/Courses.vue'


export default {
    name: 'Home',

    data: () => ({
        search: '',
    }),

    created() {
        this.GetLastestCourses()
    },

    components: {
        Courses
    },

    computed: {
        ...mapGetters(['LastestCourses', 'CourseImagePath']),
    },

    methods: {
        ...mapActions(["GetLastestCourses"]),

        // goToCourseView(course_id) {
        //     this.$router.push({ path: '/course-view/' + course_id })
        // },

        searchCourse() {
            if (this.search) {
                this.$router.push('/courses?search=' + this.search)

            }


        }



    }

};
</script>
<style scoped>
.v-text-field--outlined>>>fieldset {
    border-color: #1976d2;
}
</style>
