<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-alert v-model="showError" dense dismissible type="error" elevation="1">
                    Invalid Credentials
                    </v-alert>
                <v-card class="elevation-3">
                    <v-toolbar dark color="primary" elevation="0">
                        <v-toolbar-title>Login form</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form ref="form" @submit.prevent="submit" lazy-validation>
                            <v-text-field v-model="form.email" name="email" label="Email" type="email" placeholder="email"
                                :rules="emailRules" required></v-text-field>

                            <v-text-field v-model="form.password" name="password" label="Password" type="password"
                                :rules="checkEmptyRule('Password is Required')" placeholder="password"
                                required></v-text-field>
                            <!-- <div class="red--text" v-show="showError">Invalid Credentials</div> -->
                            <v-checkbox v-model="form.remember" label="Remember me"></v-checkbox>


                            <!-- <v-list-item class="grey--text" to="/register">Register</v-list-item> -->
                            <v-layout class="justify-space-between">
                                <v-btn type="submit" class="" color="primary" value="log in">Login</v-btn>

                                <v-btn text to="/register" class="primary--text">Register</v-btn>
                                <!-- <router-link class="text-decoration-none text-center" to="/register">Register</router-link> -->
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
    name: "Login",
    components: {},
    data() {
        return {
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],

            form: {
                email: "",
                password: "",
                remember: false
            },
            showError: false,
            role_routes: {
                student: 'home',
                instructor: 'home',
                admin: 'admin-dashboard',
            }
        };
    },

    // computed: {
    //     ...mapGetters([ 'UserRoles' ]),
    // },

    methods: {
        ...mapActions(["Login"]),
        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },
        submit() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                this.Login(this.form).then(() => {
                    this.$store.getters.HasRole('admin')
                    this.gotoRoute()
                    // this.$router.push({ name: 'admin-dashboard' });
                    // console.log('login success');
                    this.showError = false
                }).catch((error) => {
                    this.showError = true
                })
            }

            // this.Register()
        },

        gotoRoute() {
            for (const role in this.role_routes) {
                if (this.$store.getters.HasRole(role)) {
                    let route = this.role_routes[role]
                    this.$router.push({ name: route })
                    return
                }
            }
            // this.$router.push({ name: this.role_routes[this.] })
        }
    },
};
</script>
