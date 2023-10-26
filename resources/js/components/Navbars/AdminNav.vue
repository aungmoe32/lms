<template>
    <nav>

        <AppBar v-model="drawer"></AppBar>

        <v-navigation-drawer v-model="drawer" dark app>

            <!-- <v-layout column class="mb-0 blue" >
                <p class="white--text subheading text-center mb-0">LMS</p>
            </v-layout> -->

            <!-- <div class="white--text  text-center mb-0 blue text-h1">LMS</div> -->

            <v-list flat>
                <v-list-item v-for="link in links" :key="link.text" router :to="link.route" active-class="border">
                    <v-list-item-action>
                        <v-icon>mdi-{{ link.icon }}</v-icon>
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
            { icon: 'home', text: 'Home', route: '/' },
            { icon: 'view-dashboard', text: 'Dashboard', route: '/admin/dashboard' },
            { icon: 'account', text: 'User Management', route: '/admin/users' },
            { icon: 'tag', text: 'Categories', route: '/admin/categories' },
            // { icon: 'cash', text: 'Withdraw Requests', route: '/team' },
            // { icon: 'post', text: 'Blogs', route: '/team' },
            // { icon: 'cogs', text: 'Settings', route: '/team' },
            // { icon: 'credit-card-outline', text: 'Payment', route: '/team' },
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
