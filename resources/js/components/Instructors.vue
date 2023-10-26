<template>
    <v-row class="my-3 mx-5">


        <v-col xs="6" sm="6" md="3" v-for="(instructor, i) in instructors" :key="i">
            <v-card :to="'/instructors/' + instructor.id">
                <v-img
                :aspect-ratio="16/9"
                :src="InstructorImagePath(instructor)"></v-img>

                <v-card-title class="justify-center">
                    {{ InstructorName(instructor) }}
                </v-card-title>


            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex";



export default {
    props: ['instructors'],
    data: () => ({
    }),

    created() {
    },

    computed: {
        ...mapGetters(['InstructorImagePath', 'InstructorName']),
    },

    methods: {
        // ...mapActions(["GetLastestInstructors"]),

        // goToInstructorView(id) {
        //     this.$router.push('/instructors/' + id)
        // },



    }

};
</script>
<style scoped>
.v-text-field--outlined>>>fieldset {
    border-color: #1976d2;
}
</style>
