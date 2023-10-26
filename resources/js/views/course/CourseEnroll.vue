<template>
    <!-- <v-container fluid fill-height> -->
    <v-row v-if="LearningCourse && LearningLecture" class="ma-10 justify-center">
        <v-col cols="12 d-flex align-center ">
            <h1>{{ LearningCourse.course_title }}</h1>
            <v-spacer></v-spacer>
            <v-btn v-if="LearningLecture.progress == null || LearningLecture.progress.status == 0" color="success"
                @click="updateLectureStatus('true')">Mark as
                completed</v-btn>
            <v-btn v-if="LearningLecture.progress && LearningLecture.progress.status == 1" color="primary"
                @click="updateLectureStatus('false')">Completed</v-btn>
        </v-col>
        <v-col cols="12">
            <h3>{{ LearningLecture.title }}</h3>
        </v-col>



        <v-col cols="8" class="text-center" v-if="LearningLecture.lecture_content">
            <video style="width: 100%;" v-if="LearningLecture.lecture_content.type == 'video'"
                :src="file_path + LearningLecture.lecture_content.file_name" controls>
            </video>

            <audio style="width: 100%;" v-if="LearningLecture.lecture_content.type == 'audio'"
                :src="file_path + LearningLecture.lecture_content.file_name" controls>
            </audio>

            <a v-if="LearningLecture.lecture_content.type == 'document'" download
                :href="file_path + LearningLecture.lecture_content.file_name">Download</a>



            <span v-if="LearningLecture.lecture_content.type == 'text'" style="white-space: pre-line;">{{ LearningLecture.contenttext }}</span>
        </v-col>

        <v-col cols="12" class="pa-3">
            <h3 class="my-3">Description</h3>
            <span class="px-3" style="white-space: pre-line;">{{ LearningLecture.description }}</span>
        </v-col>



    </v-row>

    <!-- </v-container> -->
</template>

<script>
import { mapGetters, mapActions } from "vuex";



export default {
    name: 'CourseEnroll',
    data: () => ({
        course_id : null,
        file_path : ''

    }),

    created() {
        this.course_id = this.$route.params.course_id
        this.file_path = '/storage/course/' + this.$route.params.course_id + '/'
        this.getLecture(this.$route)
    },

    beforeRouteUpdate(to, from, next) {
        this.getLecture(to)
        next()
    },

    beforeRouteLeave(to, from, next) {
        this.$store.commit('setLearningCourse', null)
        next()
    },

    computed: {
        ...mapGetters(['LearningCourse', 'LearningLecture']),
    },

    methods: {
        ...mapActions(["GetLearningCourse", "GetLearningLecture", "UpdateLectureStatus"]),

        updateLectureStatus(status) {
            let form = {}
            form.course_id = this.LearningCourse.id
            form.lecture_id = this.LearningLecture.id
            form.status = status
            this.UpdateLectureStatus(form)
            if (!this.LearningLecture.progress) {
                this.LearningLecture.progress = { status: null }
            }
            this.LearningLecture.progress.status = status == 'true' ? 1 : 0
        },

        getLecture(route) {
            let form = {}
            form.course_id = route.params.course_id
            form.lecture_id = route.params.lecture_id
            this.GetLearningLecture(form)
        }

    }

};
</script>
