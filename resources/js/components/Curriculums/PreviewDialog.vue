<template>
    <v-dialog v-model="previewDialog" max-width="500px" persistent>
        <v-card v-if="toggle.lecture_content">

            <v-toolbar dark color="primary" class="elevation-0">
                <v-btn icon dark @click="previewDialogClose()">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Preview</v-toolbar-title>
            </v-toolbar>

            <v-card-text style="text-align: center;" class="pa-4">


                <video  style="width: 100%;" v-if="ContentType == 'video'"
                    :src="file_path + toggle.lecture_content.file_name" controls>

                </video>
                <audio  style="width: 100%;" v-if="ContentType == 'audio'"
                    :src="file_path + toggle.lecture_content.file_name" controls>
                </audio>

                <a  v-if="ContentType == 'document'" download
                    :href="file_path + toggle.lecture_content.file_name">Download</a>

                <div class="pa-1" v-if="ContentType == 'text'"
                    style="color: black; white-space: pre-line; width: 100%; text-align: start;">{{ toggle.lecture_content.contenttext }}</div>

            </v-card-text>

        </v-card>
    </v-dialog>
</template>

<script>

import { mapGetters, mapActions } from "vuex";


export default {
    props: ['toggle'],

    data: () => ({
        course_id : null,
        previewDialog: false,
        file_path : ''
    }),

    watch: {
        'toggle.on': function (ov, nv) {

            this.previewDialog = true


        }
    },

    created() {
        this.course_id = this.$route.params.course_id

        this.file_path = '/storage/course/' + this.course_id + '/'
    },


    computed: {
        ContentType(){
            return this.toggle.lecture_content.type
        }
    },
    methods: {


        previewDialogClose() {
            let medias = document.querySelectorAll('video, audio')
            medias.forEach(media => {
                media.pause()
            })
            this.previewDialog = false

        },
    }


}
</script>
