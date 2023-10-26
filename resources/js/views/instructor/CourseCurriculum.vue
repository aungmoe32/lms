<template>
    <v-container v-if="Curriculums">


        <BreadCrumbs :course_id="this.$route.params.course_id"></BreadCrumbs>

        <SectionDialog :toggle="sectionDialogT"></SectionDialog>
        <LectureDialog :toggle="lectureDialogT"></LectureDialog>
        <MediaDialog :toggle="mediaDialogT"></MediaDialog>
        <PreviewDialog :toggle="previewDialogT"></PreviewDialog>




        <v-card class=" elevation-0">
            <v-card-title>
                <span class="text-h5">Curriculum</span>
                <v-spacer></v-spacer>
                <v-btn color="success" class="" @click="addSectionBtn()">Add Section</v-btn>

            </v-card-title>

            <v-card-text>

                <v-expansion-panels accordion multiple>
                    <v-expansion-panel v-for="(section, i) in Curriculums.sections" :key="i">
                        <v-expansion-panel-header>
                            <v-col class="pa-0">
                                Section : {{ section.title }}

                                <v-btn icon @click.native.stop="editSectionBtn(section)">
                                    <v-icon small>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn icon @click.native.stop="deleteSectionBtn(section)">
                                    <v-icon small>mdi-delete</v-icon>
                                </v-btn>



                                <v-btn color="primary " elevation="0" x-small @click.native.stop="addLectureBtn(section)" fab>
                                    <v-icon>mdi-plus</v-icon>
                                </v-btn>

                            </v-col>

                        </v-expansion-panel-header>
                        <v-expansion-panel-content>

                            <v-data-table class=" elevation-1" :headers="headers"
                                :items="section.lectures" :items-per-page="5">
                                <template v-slot:item.actions="{ item }">
                                    <v-icon small @click="deleteLectureBtn(section,item)">
                                        mdi-delete
                                    </v-icon>
                                </template>

                                <template v-slot:item.title="{ item }">
                                    <span>{{ item.title }}</span>
                                    <v-icon small class="ml-3" @click="editLectureBtn(section,item)">
                                        mdi-pencil
                                    </v-icon>
                                </template>

                                <template v-slot:item.media="{ item }">
                                    <v-btn plain color="primary " @click="previewMediaBtn(section, item.lecture_content)">

                                        preview
                                    </v-btn>
                                    <v-icon small class="ml-3" @click="editMediaBtn(item)">
                                        mdi-pencil
                                    </v-icon>
                                </template>
                            </v-data-table>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>

            </v-card-text>

        </v-card>

    </v-container>
</template>
<script>
// import Popup from './Popup.vue'
import BreadCrumbs from '../../components/BreadCrumbs.vue'
import SectionDialog from '../../components/Curriculums/SectionDialog.vue'
import LectureDialog from '../../components/Curriculums/LectureDialog.vue'
import MediaDialog from '../../components/Curriculums/MediaDialog.vue'
import PreviewDialog from '../../components/Curriculums/PreviewDialog.vue'
import { mapGetters, mapActions } from "vuex";

export default {
    data: () => ({
        sectionDialogT : {
            on : false,
            editMode : false,
            section : null,
        },

        lectureDialogT : {
            on : false,
            editMode : false,
            section : null,
            lecture : null
        },

        mediaDialogT : {
            on : false,
            lecture : null
        },
        previewDialogT : {
            on : false,
            lecture_content : null
        },

        headers: [
            { text: 'ID', value: 'lecture_quiz_id' },
            { text: 'Lecture', value: 'title' },
            { text: 'Media', value: 'media', sortable: false },
            { text: 'Actions', value: 'actions', sortable: false },
        ],

        sectionDialog: false,
        lectureDialog: false,
        mediaDialog: false,
        previewDialog: false,
        newSection: {
            course_id: null,
            title: null,
            sort_order: null,
            section_id: null,
        },
        newLecture: {
            section_id: null,
            title: null,
            sort_order: null,
            lecture_id: null,
        },


        mediaForm: {
            text: null,
            file: null,
            // lecture_video : null,
            course_id: null,
            section_id: null,
            lecture_id: null
        },
        selectedMediaType: null,
        selectedMediaFile: null,
        selectedMediaLecture: null,
        selectedContentText: null,
        showMediaSelection: true,
        showMediaUpload: false,
        showMediaTextBox: false,
        // mediaRules: {
        //     defaultRule: [
        //         v => !!v || "File is required",
        //     ],
        //     textRule: [v => !!v || "Text is required",]
        // },



        mediaTypes: {
            0: {
                name: 'video',
                img: 'video1.png',
                icon: 'fas fa-video',
                text: 'Video',
                accept: 'video/*',
                upload_path: '/instructor/courses/lecturevideo/save/',
            },
            1: {
                name: 'audio',
                img: 'audio1.png',
                icon: 'fas fa-file-audio',
                text: 'Audio',
                accept: 'audio/*',
                upload_path: '/instructor/courses/lectureaudio/save/',
            },
            2: {
                name: 'document',
                img: 'file1.png',
                icon: 'fas fa-file-pdf',
                text: 'Document',
                accept: '.pdf',
                upload_path: '/instructor/courses/lecturedoc/save/',
            },
            3: {
                name: 'text',
                img: 'text1.png',
                icon: 'fas fa-align-center',
                text: 'Text',
                upload_path: '/instructor/courses/lecturetext/save/',
            },

        },


        course_id: null,
        editSection: null,
        editLecture: null,
        saveSectionBtn: false,

        allowExtensions: {
            video: ['video/mp4', 'video/quicktime'],
            audio: ['audio/mpeg', 'audio/wav', 'audio/x-m4a'],
            document: ['application/pdf'],
        }
    }),
    components: {
        BreadCrumbs,
        SectionDialog,
        LectureDialog,
        MediaDialog,
        PreviewDialog
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'Curriculums']),


    },

    created() {
        this.course_id = this.$route.params.course_id
        this.GetCourseCurriculums(this.course_id)
        this.newSection.course_id = this.course_id
    },


    methods: {
        ...mapActions(["GetCourseCurriculums", "SaveSection", "DeleteSection", "SaveLecture", "DeleteLecture",
            'UploadFile'
        ]),

        isFileValid(file, fileType) {
            return (this.allowExtensions[fileType].indexOf(file.type) > -1)
        },


        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },

        fileUploadRules(fileType) {
            return [
                v => !!this.mediaForm.file || 'File is required',
                v => (this.mediaForm.file && this.isFileValid(this.mediaForm.file, fileType)) || fileType + ' file must be valid',
            ];
        },

        previewDialogClose() {
            let medias = document.querySelectorAll('video, audio')
            medias.forEach(media => {
                media.pause()
            })
            this.previewDialog = false

        },

        GetMediaTitle(section, lecture) {
            if (lecture.media_type == 3) {
                // return lecture.contenttext
                return 'Content'
            }
            if (lecture.media) {
                if (lecture.media_type == 0) {
                    return this.Curriculums.lecturesmedia[section.section_id][lecture.lecture_quiz_id][0].video_title
                }
                else if (lecture.media_type == 1 || lecture.media_type == 2) {
                    return this.Curriculums.lecturesmedia[section.section_id][lecture.lecture_quiz_id][0].file_title
                }
            }
            else {
                return "None"
            }

        },

        previewMediaBtn(section, lecture_content) {
            if(lecture_content){
                this.previewDialogT.lecture_content = lecture_content
                this.previewDialogT.on = !this.previewDialogT.on
            }

        },

        editMediaBtn(lecture){
            this.mediaDialogT.lecture = lecture
            this.mediaDialogT.on = !this.mediaDialogT.on
        },

        // editMediaBtn(lecture) {
        //     this.selectedMediaLecture = lecture

        //     this.mediaForm.lecture_id = lecture.lecture_quiz_id
        //     this.mediaForm.course_id = this.course_id
        //     this.mediaForm.section_id = lecture.section_id
        //     // this.mediaForm.lecture_video = this.mediaForm.file

        //     this.selectedMediaType = this.mediaTypes[lecture.media_type]

        //     this.showMediaSelection = false
        //     this.showMediaUpload = false
        //     this.showMediaTextBox = false

        //     if (lecture.media_type == null) {
        //         this.showMediaSelection = true
        //     }
        //     else if (lecture.media_type == 3) {
        //         this.mediaForm.text = lecture.contenttext
        //         this.showMediaTextBox = true
        //     }
        //     else {
        //         this.showMediaUpload = true
        //     }
        //     this.mediaDialog = true

        // },

        mediaSubmit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                // console.log('valid');
                this.UploadFile({ path: this.selectedMediaType.upload_path + this.mediaForm.lecture_id, form: this.mediaForm })
                    .then((response) => {
                        let data = response.data
                        this.$set(this.selectedMediaLecture, 'media_type', data.media_type)
                        this.$set(this.selectedMediaLecture, 'media', data.media_id)
                        var medias = this.Curriculums.lecturesmedia[this.mediaForm.section_id][this.mediaForm.lecture_id]

                        if (data.media_type == 0) {
                            this.$set(medias, 0, data.course_video)
                        }

                        else if (data.media_type == 1 || data.media_type == 2) {
                            this.$set(medias, 0, data.file)
                        }
                        else if (data.media_type == 3) {
                            this.selectedMediaLecture.contenttext = this.mediaForm.text
                        }


                        this.mediaDialog = false
                        return response
                    })
                    .catch(error => {
                    })
            }
            else {
                // console.log('invalid');
                // this.formBtnLoading = false
            }
        },

        onMediaTypeSelected(mediaType) {
            this.selectedMediaType = mediaType
            this.showMediaSelection = false

            if (mediaType.name == 'text') {
                this.showMediaTextBox = true
                this.showMediaUpload = false
            }
            else {
                this.showMediaUpload = true
            }

        },

        addLectureBtn(section) {
            this.lectureDialogT.editMode = false
            this.lectureDialogT.section = section
            this.lectureDialogT.lecture = null
            this.lectureDialogT.on = !this.lectureDialogT.on
            // this.newLecture.title = ''
            // this.newLecture.lecture_id = null
            // this.newLecture.section_id = section.section_id
            // let lectures = this.Curriculums.lecturesquiz[section.section_id]
            // this.newLecture.sort_order = lectures.length + 1

            // this.lectureDialog = true
            // this.editLecture = null
        },

        editLectureBtn(section,lecture) {
            this.lectureDialogT.editMode = true
            this.lectureDialogT.section = section
            this.lectureDialogT.lecture = lecture
            this.lectureDialogT.on = !this.lectureDialogT.on

            // console.log(lecture);
            // this.newLecture.section_id = lecture.section_id
            // this.newLecture.sort_order = lecture.sort_order
            // this.newLecture.lecture_id = lecture.lecture_quiz_id
            // this.lectureDialog = true
            // this.editLecture = lecture
        },

        addSectionBtn() {
            this.sectionDialogT.editMode = false
            this.sectionDialogT.section = null
            this.sectionDialogT.on = !this.sectionDialogT.on
            // this.newSection.title = ''
            // this.newSection.sort_order = this.Curriculums.sections.length + 1
            // this.newSection.section_id = null
            // this.sectionDialog = true
            // this.editSection = null
        },
        editSectionBtn(section) {
            this.sectionDialogT.editMode = true
            this.sectionDialogT.section = section
            this.sectionDialogT.on = !this.sectionDialogT.on
            // this.newSection.title = section.title
            // this.newSection.sort_order = section.sort_order
            // this.newSection.section_id = section.section_id
            // this.sectionDialog = true
            // this.editSection = section
        },


        deleteSectionBtn(section) {
            let form = {}
            form.section_id = section.id
            form.course_id = this.course_id
            this.DeleteSection(form).then((response) => {
                let index = this.Curriculums.sections.findIndex(s => s.id == section.id)
                this.Curriculums.sections.splice(index, 1)
                // console.log(this.Curriculums.sections);
                return response
            })
                .catch(error => {
                })
        },


        deleteLectureBtn(section,lecture) {
            let form = {}
            form.lecture_id = lecture.id
            form.course_id = this.course_id

            this.DeleteLecture(form).then((response) => {
                let lectures = section.lectures
                let index = lectures.findIndex(l => l.id == lecture.id)
                // console.log(index);
                lectures.splice(index, 1)
                return response
            })
                .catch(error => {
                })
        },



        saveSection() {

            this.saveSectionBtn = true
            this.SaveSection(this.newSection).then((response) => {
                this.saveSectionBtn = false
                this.sectionDialog = false

                this.saveSections(response.data)
                // this.GetCourseCurriculums(this.$route.params.course_id)

                return response
            })
                .catch(error => {
                    this.saveSectionBtn = false
                })
        },


        saveLecture() {

            // this.saveSectionBtn = true
            this.SaveLecture(this.newLecture).then((response) => {
                // this.saveSectionBtn = false
                this.lectureDialog = false

                this.saveLectures(response.data)
                // this.GetCourseCurriculums(this.$route.params.course_id)

                return response
            })
                .catch(error => {
                    // this.saveSectionBtn = false
                })
        },


        saveLectures(lecture_id) {

            if (this.editLecture) {
                this.editLecture.title = this.newLecture.title
            }
            else {
                // console.log(this.editLecture);
                // this.newSection.lecture_id = lecture_id
                this.Curriculums.lecturesquiz[this.newLecture.section_id].push({
                    title: this.newLecture.title,
                    sort_order: this.newLecture.sort_order,
                    lecture_quiz_id: lecture_id,
                    section_id: this.newLecture.section_id
                })

                if (!this.Curriculums.lecturesmedia[this.newLecture.section_id]) {
                    this.$set(this.Curriculums.lecturesmedia, this.newLecture.section_id, {});
                }



                this.$set(this.Curriculums.lecturesmedia[this.newLecture.section_id], lecture_id, [])
            }

            // console.log(this.Curriculums.sections);
        },
        saveSections(section_id) {


            if (this.editSection) {
                this.editSection.title = this.newSection.title
            }
            else {
                // this.newSection.section_id = section_id
                // this.Curriculums.lecturesquiz[section_id] = []
                this.$set(this.Curriculums.lecturesquiz, section_id, [])

                this.Curriculums.sections.push({
                    course_id: this.$route.params.course_id,
                    title: this.newSection.title,
                    sort_order: this.newSection.sort_order,
                    section_id: section_id,
                })
            }


            // console.log(this.Curriculums.sections);
        }

    }

}



</script>
<style scoped>
.border {
    border-left: 4px solid #0ba518;
}
</style>
