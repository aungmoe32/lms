<template>
    <v-form ref="form" class="ml-10 mt-10" style="max-width: 500px;" lazy-validation v-if="instructor">
        <h1>Instructor Profile</h1>
        <v-row>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.first_name" :rules="checkEmptyRule('First Name is required')" label="Last Name"
                    type="text" placeholder="First Name" required></v-text-field>

            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.last_name" :rules="checkEmptyRule('Last Name is required')" label="Last Name"
                    type="text" placeholder="Last Name" required></v-text-field>

            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.contact_email" label="Contact Email" type="email" placeholder="Contact Email"
                    :rules="emailRules" required></v-text-field>

            </v-col>
            <v-col cols="12" md="6">
                <v-text-field v-model="form.paypal_id" label="Paypal ID" type="email" placeholder="Paypal ID"
                    :rules="emailRules" required
                    hint="* Please provide correct Paypal ID"
                    ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.telephone" :rules="phoneRules('Telephone')" label="Telephone" type="number"
                    required></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.mobile" :rules="phoneRules('Mobile')" label="Mobile" type="number"
                    required></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.link_facebook" label="Facebook Link" type="text" required></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field v-model="form.link_linkedin" label="LinkedIn Link" type="text" required></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field v-model="form.link_twitter" label="Twitter Link" type="text" required></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field v-model="form.link_googleplus" label="GooglePlus Link" type="text" required></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-textarea outlined :rules="checkEmptyRule('Biography is required')" label="Biography" v-model="form.biography"></v-textarea>

            </v-col>

            <v-col cols="6">
                <v-img
                class="elevation-1"
                style="border-radius: 3px;"
                    :aspect-ratio="16/9"
                    :src="InstructorImagePath(instructor)"></v-img>
                <!-- <img :aspect-ratio="16/9" :src="InstructorImagePath(instructor)"  class="elevation-1" style="border-radius: 3px; width: 100%;"> -->
            </v-col>
            <v-col cols="6">
                <v-form ref="form" lazy-validation>

                    <v-file-input v-model="form.instructor_image" :accept="'image/*'" :rules="checkEmptyRule('Image is required')"
                        :label="'Upload Image'" outlined dense></v-file-input>


                </v-form>
            </v-col>





            <!-- <v-col sm="12" md="6">
                <v-text-field v-model="form.new_password" :rules="passwordRules"
                             label="New Password" type="password" placNewer="New Password"
                            required></v-text-field>

            </v-col> -->


            <v-col cols="12">
                <!-- <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn> -->
                <v-btn color="primary" class="" @click="submit">
                    Submit
                </v-btn>
                <v-btn color="success" class="ml-2" :to="'/instructors/' + instructor.id">
                    View Profile
                </v-btn>
            </v-col>

            <v-col cols="12">
                <v-alert v-model="alert" dense dismissible type="success" elevation="1">
                    {{ alertMsg }}
                </v-alert>
            </v-col>
        </v-row>






    </v-form>
</template>
<script>
import { mapGetters, mapActions } from "vuex";

export default {
    data: () => ({
        alert: false,
        alertMsg: 'Profile Updated!',
        form: {
            first_name: "",
            last_name: "",
            contact_email: "",
            paypal_id: "",
            telephone: "",
            mobile: "",
            link_facebook: '',
            link_linkedin: '',
            link_twitter: '',
            link_googleplus: '',
            biography: '',
            instructor_image: null,
            // new_password: "",
        },
        instructor: null,

        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],

    }),
    components: {

    },
    computed: {
        ...mapGetters(['InstructorImagePath']),
        passwordRules() {
            return [
                // v => !!this.form.password || 'Password is required',
                v => {
                    if (v) {
                        return v.length >= 8 || 'Name must be at least 8 characters'
                    }
                    else return true
                }

            ];
        },



    },

    created() {
        this.InstrProfile()
            .then((response) => {
                this.instructor = response.data
                this.fillForm(this.instructor)

            })
            .catch()
    },

    beforeRouteUpdate(to, from, next) {
        // this.$route.params.course_id

    },

    methods: {
        ...mapActions(["Logout", 'InstrProfile', 'UploadFile']),

        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },
        phoneRules( type) {
            return [
                v => !!v || type + ' no is required',
                v => {
                    if (v) {
                        return parseInt(v) >= 0 ||  type + ' no must be valid'
                    }
                    else return true
                }

            ];
        },

        fillForm(instructor) {
            this.form.first_name = instructor.first_name
            this.form.last_name = instructor.last_name
            this.form.contact_email = instructor.contact_email
            this.form.paypal_id = instructor.paypal_id
            this.form.telephone = instructor.telephone
            this.form.mobile = instructor.mobile
            this.form.link_facebook = instructor.link_facebook
            this.form.link_linkedin = instructor.link_linkedin
            this.form.link_googleplus = instructor.link_googleplus
            this.form.link_twitter = instructor.link_twitter
            this.form.biography = instructor.biography
            // this.form.instructor_image = instructor.instructor_image
        },

        submit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                // console.log('valid');

                this.UploadFile({ path: '/instructor/profile-save', form:  this.form})
                .then(res=>{
                    this.instructor = res.data
                    this.fillForm(this.instructor)
                    this.alert = true
                })
                .catch()
            }
        }

    }


}
</script>
<style scoped>
.border {
    border-left: 4px solid #0ba518;
}
</style>
