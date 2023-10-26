<template>
    <nav>

        <AppBar v-model="drawer"></AppBar>


        <v-navigation-drawer v-model="drawer" app class="primary" dark>


            <v-list>

                <v-list-item v-for="(link, i) in drawerLinks" :key="i"
                    :to="link.route">
                    <v-list-item-action>
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
import { mapGetters, mapActions } from "vuex";
import AppBar from './AppBar.vue'

export default {
    data: () => ({
        drawer: false,



        // font-awesome
        drawerLinks: [
            { icon: 'fa-home', text: 'Home', route: '/' },
            { icon: 'fa-list', text: 'Courses', route: '/courses' },
            { icon: 'fa-book-reader', text: 'Enrolled Courses', route: '/my-courses' },
            { icon: 'fa-chalkboard-teacher', text: 'Instructors', route: '/instructor-list' },
        ],



    }),
    components: {
        AppBar
    },
    computed: {
        // ...mapGetters(['isAuthenticated', 'ActiveCategories']),
    },

    created() {
        // this.GetActiveCategories()
    },

    methods: {
        // ...mapActions(["Logout", 'GetActiveCategories', 'BecomeInstructor']),

    }


}
</script>
<style scoped>
.border {
    border-left: 4px solid #0ba518;
}
</style>
