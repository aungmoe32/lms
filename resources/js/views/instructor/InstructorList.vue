
<template>
    <v-container fluid >

        <v-row class="my-3">


            <v-col class="text-center" cols="12">
                <h2>Our Instructors</h2>
            </v-col>
        </v-row>


        <Instructors :instructors="instructors"></Instructors>


    </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import Instructors from '../../components/Instructors.vue'


export default {
    // name: 'Home',

    data: () => ({
        instructors: [],
    }),

    created() {
        this.GetInstructors()
        .then(res => {
            this.instructors = res.data
        }).catch()
    },

    components: {
        Instructors
    },

    computed: {
        // ...mapGetters(['LastestCourses', 'CourseImagePath']),
    },

    methods: {
        ...mapActions(["GetInstructors"]),




    }

};
</script>
<style scoped>
.v-text-field--outlined >>> fieldset {
  border-color: #1976d2;
}
</style>
