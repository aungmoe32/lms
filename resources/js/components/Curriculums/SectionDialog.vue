<template>
    <v-dialog v-model="sectionDialog" max-width="500px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Section Name</span>
            </v-card-title>

            <v-card-text>
                <v-text-field v-model="newSection.title" label="Name"></v-text-field>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="sectionDialog = false">
                    Cancel
                </v-btn>
                <v-btn color="blue darken-1" :disabled="disableBtn" text @click="saveSection()">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

import { mapGetters, mapActions } from "vuex";


export default {
    props: ['toggle'], // {on : false, editMode : false, editId : null, section : null}

    data: () => ({
        newSection: {
            title: '',
            section_id: '',
            course_id: '',

        },
        sectionDialog: false,
        disableBtn: false,
    }),

    watch: {
        'toggle.on': function (ov, nv) {
            // console.log('watch')
                if (this.toggle.editMode) {
                    this.newSection.title = this.toggle.section.title
                }
                else{
                    this.newSection.title = ''
                }
                this.sectionDialog = true
                // this.disableBtn = true


        }
    },

    created() {
        this.newSection.course_id = this.$route.params.course_id
    },


    computed: {
        ...mapGetters(['Curriculums']),
    },
    methods: {
        ...mapActions(["SaveSection"]),

        saveSection() {

            if (this.toggle.editMode) {
                this.newSection.section_id = this.toggle.section.id
            }
            else {
                this.newSection.section_id = null
            }



            this.SaveSection(this.newSection)
                .then((response) => {
                    let section = response.data
                    if (this.toggle.editMode) {
                        this.toggle.section.title = section.title
                    }
                    else{
                        this.$set(this.Curriculums.sections, this.Curriculums.sections.length, section)

                    }


                    return response
                })
                .then((response) => {
                    this.sectionDialog = false
                    return response
                })
        },

    }


}
</script>
