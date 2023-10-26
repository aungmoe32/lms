<template>
    <v-row class="mt-3">
        <v-col cols="12">
            <h1>{{ course.course_title }}</h1>
        </v-col>
        <v-col cols="12">
            <v-row class="justify-center">
                <v-col cols="6">

                    <v-list subheader>
                        <v-list-item v-for="(item, i) in infoList" :key="i">
                            <v-list-item-icon class="justify-center align-center mr-2">
                                <i :class="item.icon + ' my_icon'"></i>

                            </v-list-item-icon>
                            <v-list-item-content>

                                <div v-if="item.name == 'Instructor'" class="d-flex align-center">
                                    <span>Instructor : </span>
                                    <v-btn :to="'/instructors/' + course.instructor.id" class="pa-0 primary--text ml-2"
                                        plain text>{{
                                            InstructorName(course.instructor) }}</v-btn>

                                </div>
                                <div v-else-if="item.name == 'Rating'" class="d-flex align-center">
                                    <span>Rating : </span>
                                    <v-rating readonly background-color="grey" color="warning" length="5" small
                                        v-model="course.averageRating"></v-rating>

                                </div>
                                <v-list-item-title v-else-if="item.name == 'Level'"
                                    v-text="item.name + ' : ' + course.instruction_level.level"></v-list-item-title>

                                <v-list-item-title v-else-if="item.name == 'Price'"
                                    v-text="item.name + ' : ' + course[item.attr] + ' $'"></v-list-item-title>
                                <v-list-item-title v-else
                                    v-text="item.name + ' : ' + course[item.attr]"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>

                    <v-subheader>
                        Total rated :
                        {{ course.userSumRating }}
                    </v-subheader>


                </v-col>

                <v-col cols="6">
                    <v-row>
                        <v-col cols="12" class="d-flex justify-end">
                            <i style=" font-size: 50px;" class="far fa-bookmark my_icon mr-4"></i>
                            <span>
                                Category<br>
                                <span class="primary--text">
                                    {{ course.category.name }}
                                </span>
                            </span>
                        </v-col>
                        <template v-if="isAuthenticated && !HasAnyRole(['admin'])">
                            <v-col v-if="!courseLearn" cols="12" class="d-flex align-center justify-end">
                                <v-btn v-if="showEnrollBtn" class="mr-2" color="primary"
                                    :to="'/checkout/' + $route.params.course_id">Enroll
                                    Course</v-btn>
                                <span v-show="!showEnrollBtn">Course Enrolled</span>
                                <v-btn class="mx-2" v-if="!showEnrollBtn" color="primary"
                                    :to="'/course-learn/' + course.id">Learn</v-btn>

                            </v-col>
                            <v-col v-else cols="12" class="d-flex align-center justify-end">
                                <v-btn color="primary" :to="'/course-view/' + $route.params.course_id">
                                    View Course</v-btn>
                            </v-col>
                        </template>

                    </v-row>
                </v-col>

                <v-col cols="8">
                    <v-img class="elevation-1" style="border-radius: 3px;" :aspect-ratio="16 / 9"
                        :src="'/storage/course_images/' + course.course_image"></v-img>
                </v-col>
            </v-row>






        </v-col>
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex";


export default {
    props: ['course', 'courseLearn', 'showEnrollBtn', 'guest'],

    data: () => ({
        infoList: [
            {
                name: 'Duration',
                icon: 'fa fa-clock',
                attr: 'duration'
            },
            {
                name: 'Level',
                icon: 'fas fa-level-up-alt'
            },
            {
                name: 'Price',
                icon: 'fas fa-dollar-sign',
                attr: 'price'
            },
            {
                name: 'Student',
                icon: 'fas fa-users',
                attr: 'course_takens_count'
            },
            {
                name: 'Instructor',
                icon: 'fas fa-chalkboard-teacher'
            },
            {
                name: 'Rating',
                icon: 'fas fa-star'
            },
        ]
    }),

    created() {
    },


    computed: {
        ...mapGetters(['InstructorImagePath', 'InstructorName', 'isAuthenticated', 'HasAnyRole']),
    },
    methods: {
        // ...mapActions(["GetCourseImage"]),
    }


}
</script>
