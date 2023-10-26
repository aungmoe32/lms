<template>
    <v-container>
        <v-alert v-model="alert" dense dismissible type="success" elevation="1">
            {{ alertMsg }}
        </v-alert>

        <BreadCrumbs v-if="!IsCourseCreate" :course_id="this.$route.params.course_id"></BreadCrumbs>
        <v-card>
            <v-card-title>
                <span class="text-h5">Course Info</span>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field v-model="form.course_title" :rules="rules.titleRules" label="Course Title" type="text"
                        placeholder="Course Title" required></v-text-field>

                    <!-- <v-text-field v-model="form.category_id" :rules="rules.ca" label="Course Title" type="text"
                        placeholder="Course Title" required></v-text-field> -->


                    <!-- <v-text-field v-model="form.instruction_level_id" :rules="rules.ca" label="Course Title" type="text"
                        placeholder="Course Title" required></v-text-field> -->

                    <v-select v-model="form.category_id" :rules="rules.categoryRules" :items="ActiveCategories"
                        item-text="name" item-value="id" label="Select Category" single-line></v-select>

                    <v-select v-model="form.instruction_level_id" :rules="rules.instructionLevelRules"
                        :items="InstructionLevels" item-text="level" item-value="id" label="Select Instruction Level"
                        single-line></v-select>


                    <v-text-field v-model="form.duration" label="Duration" type="text"
                        placeholder="Duration"></v-text-field>


                    <v-text-field v-model="form.price" :rules="priceRules" label="Price" type="number"
                        placeholder="Price"></v-text-field>
                    <span>Enter 0 if course is free</span>

                    <v-textarea class="my-2" outlined label="Course Overview" :rules="checkEmptyRule('Course Overview is required')"
                            v-model="form.overview"></v-textarea>





                    <v-radio-group v-model="form.is_active" row>
                        <v-radio label="Active" :value="1"></v-radio>
                        <v-radio label="Inactive" :value="0"></v-radio>
                    </v-radio-group>


                    <v-layout class="justify-end">
                        <!-- <v-btn color="blue darken-1" text @click="cancel">
                            Cancel
                        </v-btn> -->
                        <v-btn :disabled="!valid || formBtnLoading" :loading="formBtnLoading" color="primary" class=""
                            @click="submit">
                            Submit
                        </v-btn>
                    </v-layout>


                </v-form>
            </v-card-text>

        </v-card>

    </v-container>
</template>
<script>
import BreadCrumbs from '../../components/BreadCrumbs.vue'
import { mapGetters, mapActions } from "vuex";

export default {
    data: () => ({
        drawer: false,
        formBtnLoading: false,
        valid: false,

        alert: false,
        alertMsg: '',
        alertType: 'success',


        // selectedCategory: null,
        // selectedInstructionLevelId: null,
        rules: {
            titleRules: [
                v => !!v || 'Name is required'
            ],
            categoryRules: [
                v => !!v || 'Category is required'
            ],
            instructionLevelRules: [
                v => !!v || 'Instruction Level is required'
            ],
            insLevelRules: [
                v => !!v || 'Level is required'
            ],
        },

        instruction_levels: null,


        form: {
            course_id: null,
            course_title: null,
            category_id: null,
            instruction_level_id: null,
            price: '0',
            duration: null,
            overview: '',
            is_active: 1
        },

        // instructorForm: {
        //     first_name: null,
        //     last_name: null,
        //     contact_email: null,
        //     telephone: null,
        //     paypal_id: null,
        //     biography: null,
        // }

    }),
    components: {
        BreadCrumbs
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'ActiveCategories', 'InstructionLevels']),
        IsCourseCreate() {
            return (!this.$route.params.course_id && this.$route.params.course_id !== 0)
        },

        priceRules() {
            return [
                v => !(this.form.price === '') || 'Price is required',
                v => parseFloat(this.form.price) >= 0 || 'Price must be at least 0',
            ];
        },
    },

    created() {
        // console.log('oncreate info');
        this.GetActiveCategories()
        this.getCourse()
    },

    beforeRouteUpdate(to, from, next) {
        // this.$route.params.course_id

    },

    methods: {
        ...mapActions(["Logout", 'GetActiveCategories', 'BecomeInstructor', 'SaveCourse', 'GetCourse']),
        logout() {
            this.Logout()

        },
        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },
        submit() {
            this.formBtnLoading = true
            this.form.course_id = this.$route.params.course_id;
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                // console.log('valid');
                this.SaveCourse(this.form)
                    .then((response) => {
                        // this.$router.push({ name: 'instructor-dashboard' })
                        this.alertMsg = response.data.message
                        this.alert = true
                        this.alertType = 'success'
                        this.formBtnLoading = false
                        this.$router.push('/instructor/course-info/' + response.data.course.id)
                    })
                    .catch(error => {
                        // this.alertMsg = response.data.message
                        this.alertMsg = "Course not found or you can't update this course"
                        this.alert = true
                        this.alertType = 'error'
                        this.formBtnLoading = false
                    })

            }
            else {
                // console.log('invalid');
                this.formBtnLoading = false
            }
        },



        getCourse() {
            let course_id = this.$route.params.course_id
            this.GetCourse(course_id).then(({ data }) => {
                if (data.course) {
                    this.form = {
                        // course_id : data.course.id,
                        course_title: data.course.course_title,
                        category_id: data.course.category_id,
                        instruction_level_id: data.course.instruction_level_id,
                        duration: data.course.duration,
                        price: data.course.price,
                        overview : data.course.overview,
                        is_active: data.course.is_active
                    }
                }
            })
        }


    }


}
</script>
<style scoped>
.border {
    border-left: 4px solid #0ba518;
}
</style>
