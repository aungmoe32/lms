<template>
    <v-app>
        <!-- <Navbar /> -->
        <!-- <Nav></Nav> -->

        <router-view name="nav"></router-view>
        <v-main>
            <router-view></router-view>
        </v-main>
        <!-- <Footer /> -->
        <v-footer v-if="$route.name == 'home'" dark padless>
            <v-card flat tile class="primary lighten-1 white--text text-center pa-5">


                <v-card-text class="white--text pt-0">
                    Phasellus feugiat arcu sapien, et iaculis ipsum elementum sit amet. Mauris cursus commodo interdum.
                    Praesent ut risus eget metus luctus accumsan id ultrices nunc. Sed at orci sed massa consectetur
                    dignissim a sit amet dui. Duis commodo vitae velit et faucibus. Morbi vehicula lacinia malesuada. Nulla
                    placerat augue vel ipsum ultrices, cursus iaculis dui sollicitudin. Vestibulum eu ipsum vel diam
                    elementum tempor vel ut orci. Orci varius natoque penatibus et magnis dis parturient montes, nascetur
                    ridiculus mus.
                </v-card-text>

                <v-divider></v-divider>

                <v-card-text class="white--text">
                    {{ new Date().getFullYear() }} — <strong>LMS</strong>
                </v-card-text>
            </v-card>
        </v-footer>
    </v-app>
</template>


<script>



export default {
    name: 'App',

    components: {
        // Navbar,
        // Nav,
        // Footer
    },

    data: () => ({
        //
    }),
};
</script>
