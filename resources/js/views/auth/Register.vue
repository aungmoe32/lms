
<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-alert v-model="showError" dense dismissible type="error" elevation="1">
                        {{ 'Register Fail!' }}
                    </v-alert>
                <v-card class="elevation-3">

                    <v-toolbar dark color="primary" elevation="0">
                        <v-toolbar-title>Register</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form" @submit.prevent="submit" lazy-validation>
                            <v-text-field v-model="form.first_name" :rules="checkEmptyRule('First Name is required')"
                                label="First Name" type="text" placeholder="First Name" required></v-text-field>


                            <v-text-field v-model="form.last_name" :rules="checkEmptyRule('Last Name is required')"
                                label="Last Name" type="text" placeholder="Last Name" required></v-text-field>


                            <v-text-field v-model="form.email" name="email" label="Email" type="email" placeholder="email"
                                :rules="emailRules" required></v-text-field>

                            <v-text-field v-model="form.password" :rules="passwordRules"
                                name="password" label="Password" type="password" placeholder="password"
                                required></v-text-field>

                            <v-text-field v-model="form.confirmPassword" name="confirmPassword"
                                label="Confirm Password" type="password" placeholder="confirm password"
                                :rules="confirmPasswordRules" required></v-text-field>


                            <v-layout class="justify-space-between mt-2" >
                                <v-btn type="submit" class="" color="primary">Register</v-btn>

                                <v-btn text to="/login" class="primary--text">Login</v-btn>
                            </v-layout>
                        </v-form>
                    </v-card-text>
                </v-card>

            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { mapActions } from "vuex";

export default {
    name: "Register",
    components: {},
    data() {
        return {
            form: {
                first_name: "",
                last_name: "",
                email: "",
                password: "",
                confirmPassword: "",
            },
            showError: false,
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
        };
    },
    computed: {

        passwordRules() {
            return [
                v => !!this.form.password || 'Password is required',
                v => (v && v.length >= 8) || 'Name must be at least 8 characters',
            ];
        },
        confirmPasswordRules() {
            return [
                v => !!this.form.confirmPassword || 'Confirm Password is required',
                v=> (this.form.password == v) || 'Passwords must match'
            ];
        }
    },
    methods: {
        ...mapActions(["Register"]),

        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },




        submit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                this.Register(this.form).then(() => {
                    this.$router.push("/");
                    // console.log('register success');
                    this.showError = false
                }).catch((error) => {
                    this.showError = true
                })
            }


        },
    },
};
</script>
