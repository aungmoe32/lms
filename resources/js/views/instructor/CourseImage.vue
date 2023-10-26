<template>
    <v-container>
        <v-row class="justify-center mx-10">
            <v-col cols="12 mt-3">
                <!-- <h1>{{ course.course_title }}</h1> -->
                <BreadCrumbs :course_id="this.$route.params.course_id"></BreadCrumbs>

            </v-col>
            <v-col cols="6">
                <v-img
                class="elevation-1"
                style="border-radius: 3px;"
                    :aspect-ratio="16/9"
                    :src="imagePath"></v-img>

                <!-- <img
                :aspect-ratio="16/9"
                :src="imagePath"  class="elevation-1" style="border-radius: 3px; width: 100%;"> -->
            </v-col>
            <v-col cols="6">
                <v-form ref="form" lazy-validation>

                    <v-file-input v-model="form.image" :accept="'image/*'" :rules="checkEmptyRule('Image is required')"
                        :label="'Upload Course Image'" outlined dense></v-file-input>

                    <v-btn color="primary" class="" @click="mediaSubmit">
                        Upload
                    </v-btn>

                </v-form>
            </v-col>


        </v-row>
    </v-container>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import BreadCrumbs from '../../components/BreadCrumbs.vue'



export default {
    // name: 'CourseView',
    data: () => ({
        // course: null,
        imagePath: '',
        form: {
            image: null,
            course_id: null
        },
        uploadPath : '/instructor/course-image-save'
    }),

    created() {
        this.form.course_id = this.$route.params.course_id
        this.GetCourseImage(this.$route.params.course_id)
            .then(res => {
                this.imagePath = res.data
            })
            .catch(error => { })
    },
    components: {
        BreadCrumbs
    },

    computed: {
        // ...mapGetters(['Course']),
    },

    methods: {
        ...mapActions(["GetCourseImage", "UploadFile"]),
        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },
        mediaSubmit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                this.UploadFile({path : this.uploadPath, form: this.form})
                .then(res => {
                    this.imagePath = res.data
                })
                .catch()
            }
        }


    }

};
</script>

