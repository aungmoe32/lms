<template>
    <v-container class="pa-5" v-if="Dashboard">
        <h1 class="subheading black--text mb-5">Dashboard</h1>

        <v-layout row wrap>
            <v-flex xs12 sm4 lg4>
                <v-card class="ma-3 d-flex justify-space-between pa-5 " color="error">

                    <div class="white--text">
                        <!-- <h2>{{ metrics.courses ? metrics.courses : '...'  }} Courses</h2> -->
                        <h2>{{ Dashboard.course_count }} Courses</h2>
                        <span>In Total</span>
                    </div>

                    <!-- <v-icon style="opacity: 0.1;" size="80" color="black">mdi-laptop-account</v-icon> -->
                    <!-- <i  class="icon fa fa-chalkboard"></i> -->
                </v-card>
            </v-flex>
            <v-flex xs12 sm4 lg4>
                <v-card class="ma-3 d-flex justify-space-between pa-5 " color="info">

                    <div class="white--text">
                        <h2>{{ Dashboard.instructor_count }} Instructors</h2>
                        <span>In total</span>
                    </div>

                    <!-- <v-icon style="opacity: 0.1;" size="100" color="black">mdi-laptop-account</v-icon> -->
                    <!-- <i  class="icon fas fa-bullhorn"></i> -->
                </v-card>
            </v-flex>
            <v-flex xs12 sm4 lg4>
                <v-card class="ma-3 d-flex justify-space-between pa-5 " color="success">

                    <div class="white--text">
                        <h2>{{ Dashboard.user_count }} Users</h2>
                        <span>In total</span>
                    </div>

                    <!-- <v-icon style="opacity: 0.1;" size="100" color="black">mdi-laptop-account</v-icon> -->
                    <!-- <i  class="icon far fa-play-circle"></i> -->
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";



export default {
    data: () => ({
    }),

    created() {
        this.GetDashboard()
    },

    computed: {
        ...mapGetters(['Dashboard']),
    },

    methods: {
        ...mapActions(["GetDashboard"]),





    }

};
</script>
