<template>
    <v-app-bar color="white" app class="elevation-1">

        <v-dialog v-model="dialogForm" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Become an Instructor</span>
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field v-model="instructorForm.first_name" :rules="checkEmptyRule('First Name is required')"
                            label="First Name" type="text" placeholder="First Name" required></v-text-field>

                        <v-text-field v-model="instructorForm.last_name" :rules="checkEmptyRule('Last Name is required')"
                            label="Last Name" type="text" placeholder="Last Name" required></v-text-field>

                        <v-text-field v-model="instructorForm.contact_email" :rules="emailRules" label="Contact E-mail"
                            type="email" required></v-text-field>

                        <v-text-field v-model="instructorForm.telephone" :rules="checkEmptyRule('Telephone is required')"
                            label="Telephone" type="number" required></v-text-field>

                        <v-text-field v-model="instructorForm.paypal_id" :rules="emailRules" label="Paypal ID"
                            required></v-text-field>

                        <v-textarea outlined label="Biography" :rules="checkEmptyRule('Biography is required')"
                            v-model="instructorForm.biography"></v-textarea>


                        <v-layout class="justify-end">
                            <v-btn color="blue darken-1" text @click="cancel">
                                Cancel
                            </v-btn>
                            <v-btn :disabled="!valid || formBtnLoading" :loading="formBtnLoading" color="primary" class=""
                                @click="instructorSubmit">
                                Submit
                            </v-btn>
                        </v-layout>


                    </v-form>
                </v-card-text>

                <!-- <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="cancel">
                            Cancel
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="save">
                            Save
                        </v-btn>
                    </v-card-actions> -->
            </v-card>
        </v-dialog>


        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <!-- <v-icon @click.stop="drawer = !drawer">mdi-menu</v-icon> -->
        <v-toolbar-title class="text-uppercase ">
            <router-link to="/" style="text-decoration: none;">
                <h1  class="mx-3 primary--text" style="font-size: x-large;">LMS</h1>
            </router-link>

        </v-toolbar-title>
        <v-spacer></v-spacer>

        <!-- <v-btn color="primary" to="/instructor">instructor</v-btn>
        <v-btn color="primary" to="/admin">admin</v-btn> -->

        <v-btn v-if="ShowBecomeInstructor" color="primary--text" text @click="dialogForm = true">
            Become Instructor
        </v-btn>

        <!-- <v-text-field v-model="name" :rules="nameRules" label="Name" required></v-text-field> -->

        <!-- <v-btn text class="primary--text" v-show="isAuthenticated">Become Instructor</v-btn> -->


        <v-btn outlined color="primary" v-show="!isAuthenticated" to="/login">Login/Sign Up</v-btn>

        <v-menu offset-y v-if="isAuthenticated">
            <template v-slot:activator="{ on }">
                <v-btn text v-on="on">
                    <v-icon left>mdi-chevron-down</v-icon>
                    <!-- <span>User</span> -->
                    <i class="fas fa-user my_icon"></i>
                </v-btn>
            </template>
            <v-list flat>
                <v-list-item exact-active-class="primary--text" v-for="link in menuLinks" :key="link.text" router :to="link.route">
                    <!-- <v-list-item-title>{{ link.text }}</v-list-item-title> -->
                    <v-list-item-action>
                        <v-icon>mdi-{{ link.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{ link.text }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-on:click="logout">
                    <!-- <v-list-item-title>{{ link.text }}</v-list-item-title> -->
                    <v-list-item-action>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-app-bar>
</template>
<script>
// import Popup from './Popup.vue'

import { mapGetters, mapActions } from "vuex";


export default {
    data: () => ({
        valid: false,
        dialogForm: false,
        formBtnLoading: false,
        instructorForm: {
            first_name: null,
            last_name: null,
            contact_email: null,
            telephone: null,
            paypal_id: null,
            biography: null,
        },
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],

        // font -mdi
        menuLinks: [
            // { icon: 'home', text: 'Home', route: '/' },
            { icon: 'account', text: 'Account', route: '/profile' },
            { icon: 'human-male-board', text: 'Instructor', route: '/instructor' },
            { icon: 'account-tie', text: 'Admin', route: '/admin' },
        ],

    }),

    props: ['value'],

    computed: {
        ...mapGetters(['isAuthenticated' , 'HasAnyRole']),

        ShowBecomeInstructor(){
            return this.isAuthenticated && !this.HasAnyRole(['admin', 'instructor' ])
        },

        drawer: {
            get() {
                return this.value
            },
            set(value) {
                this.$emit('input', value)
            }
        }

    },

    methods: {
        ...mapActions(["Logout", 'GetActiveCategories', 'BecomeInstructor']),
        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },

        logout() {
            this.Logout()
            this.$router.push('/')
        },
        instructorSubmit() {
            this.formBtnLoading = true
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                // console.log('valid');
                this.BecomeInstructor(this.instructorForm)
                    .then((response) => {
                        this.$router.push({ name: 'instructor-dashboard' })
                    })
                    .catch(error => {
                        this.formBtnLoading = false
                    })

            }
            else {
                // console.log('invalid');
                this.formBtnLoading = false
            }
        },

        cancel() {
            this.instructorForm = {
                first_name: null,
                last_name: null,
                contact_email: null,
                telephone: null,
                paypal_id: null,
                biography: null,
            }
            this.dialogForm = false
        },
    }


}
</script>
<style scoped>
.border {
    border-left: 4px solid #fffb00;
}
</style>
