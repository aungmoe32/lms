<template>
    <nav>
        <v-app-bar color="white" app elevation="0">
            <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->
            <v-toolbar-title class="text-uppercase ">
                <router-link to="/" style="text-decoration: none;">
                    <h1 class="mx-3 primary--text" style="font-size: x-large;">LMS</h1>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn  color="primary" outlined to="/">Courses</v-btn>

        </v-app-bar>


    </nav>
</template>
<script>

export default {

}
</script>
