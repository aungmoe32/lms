<template>
    <div>





        <v-dialog v-model="mediaDialog" max-width="500px" persistent>
            <v-card>


                <v-toolbar dark color="primary" class="elevation-0">
                    <v-btn icon dark @click="mediaDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Select Content</v-toolbar-title>
                </v-toolbar>

                <v-card-text class="pa-4">
                    <v-container>
                        <v-row v-show="showMediaSelection">
                            <v-col v-for="(mediaType, index) in mediaTypes" :key="index">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn class="mx-2 elevation-0" fab large color="primary" v-bind="attrs" v-on="on"
                                            @click="onMediaTypeSelected(mediaType)">
                                            <i style="font-size: large;" :class="mediaType.icon"></i>
                                        </v-btn>
                                    </template>
                                    <span>{{ mediaType.name }}</span>
                                </v-tooltip>

                            </v-col>

                        </v-row>

                        <v-form ref="form" v-show="!showMediaSelection" lazy-validation>

                            <v-file-input v-model="form.file" :accept="selectedMediaType.accept"
                                :rules="fileUploadRules(selectedMediaType.name)"
                                :label="'Upload Your ' + selectedMediaType.text" v-if="showMediaUpload" outlined
                                dense></v-file-input>

                            <v-textarea v-if="!showMediaUpload" outlined label="Enter Text" v-model="form.text"
                                :rules="checkEmptyRule('Text is required')"></v-textarea>

                            <v-btn color="primary" tile class="" @click="uploadSubmit">
                                Upload
                            </v-btn>

                        </v-form>

                    </v-container>
                </v-card-text>

            </v-card>
        </v-dialog>
    </div>
</template>

<script>

import { mapGetters, mapActions } from "vuex";


export default {
    props: ['toggle'],

    data: () => ({
        form: {
            lecture_id: null,
            course_id: null,
            file: null,
            text: ''
        },
        upload_path : '/instructor/courses/lecture-content/save',
        selectedMediaType: null,

        mediaDialog: false,
        showMediaSelection: false,
        showMediaUpload: false,
        mediaTypes: [
            {
                name: 'video',
                img: 'video1.png',
                icon: 'fas fa-video',
                text: 'Video',
                accept: 'video/*',
                // upload_path: '/instructor/courses/lecturevideo/save/',
            },
            {
                name: 'audio',
                img: 'audio1.png',
                icon: 'fas fa-file-audio',
                text: 'Audio',
                accept: 'audio/*',
                // upload_path: '/instructor/courses/lectureaudio/save/',
            },
            {
                name: 'document',
                img: 'file1.png',
                icon: 'fas fa-file-pdf',
                text: 'Document',
                accept: '.pdf',
                // upload_path: '/instructor/courses/lecturedoc/save/',
            },
            {
                name: 'text',
                img: 'text1.png',
                icon: 'fas fa-align-center',
                text: 'Text',
                // upload_path: '/instructor/courses/lecturetext/save/',
            },

        ],
        allowExtensions: {
            video: ['video/mp4', 'video/quicktime'],
            audio: ['audio/mpeg', 'audio/wav', 'audio/x-m4a'],
            document: ['application/pdf'],
        }
    }),

    watch: {
        'toggle.on': function (ov, nv) {
            let content = this.toggle.lecture.lecture_content
            if (content) {
                this.selectedMediaType = this.mediaTypes.find((t) => t.name == content.type)
                this.showMediaSelection = false;
                this.showMediaUpload = content.type != 'text'
            }
            else{
                this.showMediaSelection = true;
            }
            this.mediaDialog = true;

        }
    },

    created() {
        this.form.course_id = this.$route.params.course_id
    },


    computed: {
        ...mapGetters(['Curriculums']),
    },
    methods: {
        ...mapActions(["UploadFile"]),

        onMediaTypeSelected(mediaType) {
            this.selectedMediaType = mediaType
            this.showMediaSelection = false
            this.showMediaUpload = mediaType.name != "text"
        },

        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },

        fileUploadRules(fileType) {
            return [
                v => !!this.form.file || 'File is required',
                v => (this.form.file && this.isFileValid(this.form.file, fileType)) || fileType + ' file must be valid',
            ];
        },

        isFileValid(file, fileType) {
            return (this.allowExtensions[fileType].indexOf(file.type) > -1)
        },

        uploadSubmit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                let lecture = this.toggle.lecture
                this.form.type = this.selectedMediaType.name
                this.form.lecture_id = lecture.id
                this.form.course_id = this.$route.params.course_id
                // if(lecture.lecture_content){
                //     this.form.content_id  = lecture.lecture_content.id
                // }else{
                //     this.form.content_id = null
                // }
                let data = {
                    path: this.upload_path,
                    form: this.form
                }
                this.UploadFile(data)
                    .then((response) => {
                        let content = response.data
                        lecture.lecture_content = content
                        this.mediaDialog = false
                        return response
                    })
                    .catch(error => {
                    })
            }
        }

    }


}
</script>
