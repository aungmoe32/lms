<template>
    <div class="mt-5">
        <v-dialog v-model="delDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="text-h5">
                    Delete Course
                </v-card-title>
                <v-card-text>Are you sure you want to delete?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="delDialog = false">
                        Cancel
                    </v-btn>
                    <v-btn color="green darken-1" text @click="deleteCourse()">
                        Yes
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- <h1 class="subheading grey--text px-5">Categories</h1> -->
        <v-alert v-model="alert" dense dismissible type="error" elevation="1">
            {{ alertMsg }}
        </v-alert>
        <v-container>
            <h1>Courses</h1>
            <v-btn color="success" class="mt-5 mb-2" to="/instructor/course-info">Add Course</v-btn>
            <v-data-table class=" elevation-1" :headers="headers" :items="Courses" :items-per-page="5">

                <template v-slot:item.status="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'" dark>
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                    </v-chip>
                </template>
                <template v-slot:item.preview="{ item }">
                    <v-btn :to="'/course-view/' + item.id" class="" text icon color="primary lighten-2">
                        <v-icon>mdi-eye</v-icon>
                    </v-btn>
                </template>
                <template v-slot:item.price="{ item }">
                    <v-chip>
                        {{ parseFloat(item.price) ? item.price + ' $' : 'Free' }}
                    </v-chip>
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editCourse(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="delCourseBtn(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-container>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";



export default {
    name: 'CourseList',
    components: {

    },
    data: () => ({
        headers: [
            {
                text: 'id',
                value: 'id',
            },
            { text: 'Title', value: 'course_title' },
            { text: 'Category', value: 'category_name' },
            { text: 'Price', value: 'price' },
            // { text: 'Instruction Level', value: 'instruction_level' },
            { text: 'Duration', value: 'duration' },
            { text: 'Status', value: 'status' },
            { text: 'Preview', value: 'preview' },
            { text: 'Actions', value: 'actions', sortable: false },
        ],

        alert: false,
        delDialog: false,
        selectedCourse: null,
        alertMsg: ''
    }),

    created() {
        this.GetCourses()
    },

    computed: {
        ...mapGetters(['Courses']),
    },

    methods: {
        ...mapActions(['GetCourses', 'DeleteCourse']),
        deleteCourse(course) {

            this.DeleteCourse(this.selectedCourse.id)
                .then((response) => {
                    this.GetCourses()

                }, (response) => {
                    this.alertMsg = response.data.message
                    this.alert = true
                })
            this.delDialog = false
        },

        editCourse(course) {
            this.$router.push('/instructor/course-info/' + course.id)
        },

        delCourseBtn(course) {
            this.selectedCourse = course
            this.delDialog = true;
        }
    }
}
</script>

