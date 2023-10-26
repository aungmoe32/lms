<template>
    <v-container  >
        <h2 class="my-2">My Courses</h2>
        <Courses :courses="MyCourses" :goTo="clickRoute"></Courses>

    </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import Courses from '../../components/Courses.vue'


export default {
    // name: 'MyCourses',
    data: () => ({
        clickRoute : '/course-learn/'
    }),

    created() {
        this.GetMyCourses()

    },

    computed: {
        ...mapGetters(['MyCourses']),
    },

    components: {
        Courses
    },

    methods: {
        ...mapActions(["GetMyCourses"]),



    }

};
</script>
