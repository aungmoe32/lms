<template>
    <v-form ref="form" class="ml-10 mt-10" style="max-width: 500px;" lazy-validation v-if="user">
        <h1>Edit Account Profile</h1>
        <v-row>
            <v-col cols="12">
                <v-alert v-model="alert" dense dismissible type="success" elevation="1">
                    {{ alertMsg }}
                </v-alert>
            </v-col>
            <v-col cols="12" md="6">
                <v-text-field v-model="form.first_name" :rules="checkEmptyRule('First Name is required')" label="Last Name"
                    type="text" placeholder="First Name" required></v-text-field>

            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.last_name" :rules="checkEmptyRule('Last Name is required')" label="Last Name"
                    type="text" placeholder="Last Name" required></v-text-field>

            </v-col>

            <v-col cols="12" md="6">
                <v-text-field v-model="form.email" name="email" label="Email" type="email" placeholder="email"
                    :rules="emailRules" required></v-text-field>

            </v-col>
            <v-col cols="12" md="6">
                <v-text-field v-model="form.password" :rules="passwordRules" label="Password" type="password"
                    placeholder="Password" required></v-text-field>

            </v-col>
            <v-col cols="12" md="6">
                Roles :
                <v-chip class="ma-2" color="primary" v-for="role in user.roles" :key="role.id">
                    {{role.name}}
                </v-chip>
                <!-- <span v-for="role in user.roles">{{ role.name }} </span> -->
            </v-col>


            <v-layout class="justify-end">
                <!-- <v-btn color="blue darken-1" text @click="cancel">
                            Cancel
                        </v-btn> -->
                <v-btn color="primary" class="" @click="submit">
                    Submit
                </v-btn>
            </v-layout>
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
            email: "",
            // new_password: "",
        },
        user: null,

        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],

    }),
    components: {

    },
    computed: {
        ...mapGetters(['ProfileUser']),
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
        this.Profile()
            .then((response) => {
                this.user = response.data
                this.fillForm(this.user)

            })
            .catch()
    },

    beforeRouteUpdate(to, from, next) {
        // this.$route.params.course_id

    },

    methods: {
        ...mapActions(["Logout", 'Profile', 'SaveProfile']),

        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },

        fillForm(user) {
            this.form.first_name = user.first_name
            this.form.last_name = user.last_name
            this.form.email = user.email
        },

        submit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                this.SaveProfile(this.form)
                    .then((response) => {
                        this.user = response.data
                        this.fillForm(this.user)
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
