<template>
    <nav>
        <v-app-bar color="white" app class="elevation-1">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <!-- <v-icon @click.stop="drawer = !drawer">mdi-menu</v-icon> -->
            <v-btn tile text :to="'/course-learn/' + $route.params.course_id">Back to course</v-btn>
            <v-spacer></v-spacer>


        </v-app-bar>


        <v-navigation-drawer v-model="drawer" app dark>

            <!-- <v-layout column class="mb-0 blue" >
                <p class="white--text subheading text-center mb-0">LMS</p>
            </v-layout> -->

            <!-- <div class="white--text  text-center mb-0 blue text-h1">LMS</div> -->
            <v-layout class="justify-center primary pa-5 ma-0">
                <h2 class="white--text">LMS</h2>

            </v-layout>

            <v-list v-if="LearningCourse">
                <v-list-group :value="true" v-for="(section, i) in LearningCourse.sections" :key="i">
                    <template v-slot:activator>
                        <v-list-item-title>{{ section.title }}</v-list-item-title>
                    </template>

                    <v-list-item v-for="(lecture, i) in section.lectures" class="pl-10" :key="i" :to="'/course-enroll/' + $route.params.course_id + '/' + lecture.id">

                        <i class="fa fa-arrow-right" style="color: white;"></i>
                        <v-list-item-title v-text="lecture.title" class="ml-3"></v-list-item-title>


                    </v-list-item>

                </v-list-group>
            </v-list>
        </v-navigation-drawer>
    </nav>
</template>
<script>
// import Popup from './Popup.vue'
import { mapGetters, mapActions } from "vuex";

export default {
    data: () => ({
        drawer : false,
    }),
    components: {
        // Popup
    },
    computed: {
        ...mapGetters(['LearningCourse']),
    },

    created() {
        // if (!this.LearningCourse) {

            this.GetLearningCourse(this.$route.params.course_id)
        // }
    },

    methods: {
        ...mapActions(["GetLearningCourse"]),
    }


}
</script>
<style scoped>
.border {
    border-left: 4px solid yellow;
}
</style>
