<template>
    <v-row v-if="LearningCourse" class="justify-center mx-2 mx-sm-10">
        <v-col cols="12">
            <CourseInfo :course-learn="true" :course="LearningCourse"></CourseInfo>

        </v-col>

        <v-col :cols="$vuetify.breakpoint.mobile ? 12 : 8" class="">

            <div class="my-5">
                <h3 class="my-3">Course Overview</h3>
                <p class="px-3" style="white-space: pre-line;">{{ LearningCourse.overview }}</p>
            </div>

            <h3 class="mb-5">Curriculum</h3>
            <v-expansion-panels accordion>
                <v-expansion-panel v-for="(section, i) in LearningCourse.sections" :key="i">
                    <v-expansion-panel-header>
                        {{ section.title }}
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>

                        <v-data-table class=" elevation-1" :headers="headers" :items="section.lectures" :items-per-page="5">

                            <template v-slot:item.status="{ item }">
                                <!-- <span>{{ item.progress. }}</span> -->
                                <i v-if="item.progress && item.progress.status" :class="'fa fa-check-circle '"
                                    style="color: green;"></i>

                            </template>

                            <template v-slot:item.title="{ item }">
                                <!-- <span>{{ item.progress. }}</span> -->
                                <i  v-if="item.lecture_content" :class="mediaTypeIcon[item.lecture_content.type] + ' my_icon'"
                                 ></i>
                                <span class="ml-2">{{ item.title }}</span>

                            </template>

                            <template v-slot:item.learn="{ item }">
                                <v-btn small dense color="primary"
                                    :to="'/course-enroll/' + $route.params.course_id + '/' + item.id">Start</v-btn>
                            </template>
                        </v-data-table>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>


        </v-col>



    </v-row>
</template>

<script>
import CourseInfo from "../../components/CourseInfo.vue"
import { mapGetters, mapActions } from "vuex";



export default {
    name: 'CourseLearn',
    components: {
        CourseInfo,
    },
    data: () => ({
        // course: null,
        headers: [
            { text: 'Title', value: 'title' },
            { text: 'Progress', value: 'status', sortable: false },
            { text: 'Learn', value: 'learn', sortable: false },
        ],
        mediaTypeIcon: {
            'video' : 'fas fa-video',
            'audio' : 'fas fa-volume-up',
            'document' : 'far fa-file-alt',
            'text' : 'fas fa-align-center',
        }
    }),

    created() {
        this.GetLearningCourse(this.$route.params.course_id)
    },

    computed: {
        ...mapGetters(['LearningCourse']),
    },

    methods: {
        ...mapActions(["GetLearningCourse"]),



    }

};
</script>


