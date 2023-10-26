<template>
    <v-row v-if="course" class="justify-center mx-2 mx-sm-10">
        <v-col cols="12">
            <CourseInfo :course-learn="false" :show-enroll-btn="showEnrollBtn" :course="course"></CourseInfo>
        </v-col>




        <v-dialog v-model="dialog" persistent max-width="290">
            <v-card>
                <v-card-title class="text-h5">
                    Rate?
                </v-card-title>
                <v-card-text>Are you sure you want to rate this course {{ rateForm.rating }} stars?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="Cancel()">
                        No
                    </v-btn>
                    <v-btn color="green darken-1" text @click="rate()">
                        Yes
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


        <v-col :cols="$vuetify.breakpoint.mobile ? 12 : 8" class="">
            <div class="my-5">
                <h3 class="my-3">Course Overview</h3>
                <p class="px-3" style="white-space: pre-line;">{{ course.overview }}</p>
            </div>

            <div class="my-5">

                <h3 class="my-3">Course Curriculum</h3>
                <v-expansion-panels accordion>
                    <v-expansion-panel v-for="(section, i) in course.sections" :key="i">
                        <v-expansion-panel-header>
                            {{ section.title }}
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>

                            <v-data-table class=" elevation-1" :headers="headers" :items="section.lectures"
                                :items-per-page="5">
                                <template v-slot:item.title="{ item }">
                                    <!-- <span>{{ item.progress. }}</span> -->
                                    <i v-if="item.lecture_content" :class="mediaTypeIcon[item.lecture_content.type] + ' my_icon'"></i>
                                    <span class="ml-2">{{ item.title }}</span>

                                </template>
                            </v-data-table>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </div>

            <v-divider class="my-2"></v-divider>

            <div v-if="isAuthenticated" class="d-flex justify-center">
                <div class="d-flex flex-column justify-center">
                    <v-subheader>Rate this course</v-subheader>
                    <v-rating background-color="grey" @input="ratingChanged" color="warning" length="5" dense
                        v-model="rateForm.rating"></v-rating>
                </div>


            </div>

            <CommentSection class="mt-3" :course_id="this.$route.params.course_id"></CommentSection>






        </v-col>


    </v-row>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
import CourseInfo from "../../components/CourseInfo.vue"
import CommentSection from "../../components/CommentSection.vue"


export default {
    name: 'CourseView',


    components: {
        CourseInfo,
        CommentSection
    },

    data: () => ({
        course: {
            instructor: {},
            instruction_level: { level: '' },
            category: { name: '' }
        },
        dialog: false,
        orgRating: 0,
        rateForm: {
            rating: 0,
            course_id: 0
        },
        showEnrollBtn: false,
        headers: [
            { text: 'Title', value: 'title' },
            // { text: 'Type', value: 'media_type' },
        ],
        mediaTypeIcon: {
            'video': 'fas fa-video',
            'audio': 'fas fa-volume-up',
            'document': 'far fa-file-alt',
            'text': 'fas fa-align-center',
        }
    }),

    created() {
        this.rateForm.course_id = this.$route.params.course_id


        this.GetCourseViewInfo(this.$route.params.course_id)
            .then(res => {
                this.course = res.data
            })
            .catch(error => { })

        if(this.isAuthenticated){
            this.IsEnroll(this.$route.params.course_id)
            .then(res => {
                this.showEnrollBtn = res.data ? false : true

            })
            .catch(error => { })

            this.GetCourseRating(this.$route.params.course_id)
            .then((response) => {
                if (response.data != null) {
                    this.orgRating = response.data.rating
                    this.rateForm.rating = this.orgRating
                }
                return response
            })
        }



    },

    // computed: mapState([
    //     'course_rating'
    // ]),

    computed: {
        ...mapGetters(['isAuthenticated']),

    },
    methods: {
        ...mapActions(["GetCourseViewInfo", 'IsEnroll', 'SaveRating', 'GetCourseRating']),
        ratingChanged(num) {
            this.dialog = true
        },

        Cancel() {
            this.rateForm.rating = this.orgRating
            this.dialog = false
        },

        rate() {
            this.SaveRating(this.rateForm)
                .then((response) => {
                    this.orgRating = response.data.rating
                    return response
                })
            this.dialog = false
        }


    }


};
</script>


