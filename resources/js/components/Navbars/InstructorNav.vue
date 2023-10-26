<template>
    <nav>
        <AppBar v-model="drawer"></AppBar>
        <v-navigation-drawer v-model="drawer" dark app>

            <!-- <v-layout column class="mb-0 blue" >
                <p class="white--text subheading text-center mb-0">LMS</p>
            </v-layout> -->

            <!-- <div class="white--text  text-center mb-0 primary text-h1">SLMS</div> -->

            <v-list flat>
                <v-list-item v-for="link in links" :key="link.text" router :to="link.route" active-class="border">
                    <v-list-item-action>
                        <!-- <v-icon>mdi-{{ link.icon }}</v-icon> -->
                        <i :class="'fa ' + link.icon"></i>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ link.text }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>


            </v-list>
        </v-navigation-drawer>
    </nav>
</template>
<script>
// import Popup from './Popup.vue'

import { mapActions } from "vuex";
import AppBar from './AppBar.vue'


export default {

    data: () => ({
        drawer: false,
        links: [
            { icon: 'fa-home', text: 'Home', route: '/' },
            { icon: 'fa-chart-line', text: 'Dashboard', route: '/instructor/dashboard' },
            { icon: 'fa-user', text: 'Profile', route: '/instructor/profile' },
            { icon: 'fa-chalkboard', text: 'My Courses', route: '/instructor/course-list' },
            { icon: 'fa-chalkboard-teacher', text: 'Instructors', route: '/instructor-list' },
        ],


    }),
    components: {
        AppBar
    },

    methods: {
    }


}
</script>
<style scoped>
.border {
    border-left: 4px solid #fffb00;
}
</style>
