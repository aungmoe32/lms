<template>
    <v-dialog v-model="lectureDialog" max-width="500px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Lecture Name</span>
            </v-card-title>

            <v-card-text>
                <v-text-field v-model="newLecture.title" label="Name"></v-text-field>

                <v-textarea outlined label="Description" v-model="newLecture.description"></v-textarea>

            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="lectureDialog = false">
                    Cancel
                </v-btn>
                <v-btn color="blue darken-1"  text @click="saveLecture()">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

import { mapGetters, mapActions } from "vuex";


export default {
    props: ['toggle'], // {on : false, editMode : false, editId : null, lecture : null}

    data: () => ({
        newLecture: {
            title: '',
            section_id: '',
            course_id: '',
            description : ''

        },
        lectureDialog: false,
    }),

    watch: {
        'toggle.on': function (nv, ov) {
            // console.log('watch')
                if (this.toggle.editMode) {
                    this.newLecture.title = this.toggle.lecture.title
                    this.newLecture.description = this.toggle.lecture.description
                }
                else{
                    this.newLecture.title = ''
                    this.newLecture.description = ''
                }
                this.lectureDialog = true


        }
    },

    created() {
        this.newLecture.course_id = this.$route.params.course_id
    },


    computed: {
        ...mapGetters(['Curriculums']),
    },
    methods: {
        ...mapActions(["SaveLecture"]),

        saveLecture() {
            this.newLecture.section_id = this.toggle.section.id
            if (this.toggle.editMode) {
                this.newLecture.lecture_id = this.toggle.lecture.id
            }
            else {
                this.newLecture.lecture_id = null
            }


            this.SaveLecture(this.newLecture)
                .then((response) => {
                    let lecture = response.data
                    if (!this.toggle.editMode) {
                        let lectures = this.toggle.section.lectures
                        this.$set(lectures, lectures.length, lecture)
                    }
                    else{
                        this.toggle.lecture.title = lecture.title
                        this.toggle.lecture.description = lecture.description
                    }


                    return response
                })
                .then((response) => {
                    this.lectureDialog = false
                    return response
                })
        },



    }


}
</script>
