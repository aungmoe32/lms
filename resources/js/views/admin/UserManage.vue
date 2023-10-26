<template>
    <div class="mt-5">

        <v-dialog v-model="delDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="text-h5">
                    Delete User
                </v-card-title>
                <v-card-text>Are you sure you want to delete?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="delDialog = false">
                        Cancel
                    </v-btn>
                    <v-btn color="green darken-1" text @click="deleteUser()">
                        Yes
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="userFormDialog" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">User</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-form ref="form" lazy-validation>
                            <v-row>
                                <v-col cols="12" sm="6">
                                    <v-text-field v-model="newUser.first_name"
                                        :rules="checkEmptyRule('First Name is required')" label="First Name"></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6">
                                    <v-text-field v-model="newUser.last_name"
                                        :rules="checkEmptyRule('Last Name is required')" label="Last Name"></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6">
                                    <v-text-field v-model="newUser.email" name="email" label="Email" type="email"
                                        placeholder="email" :rules="emailRules" required></v-text-field>

                                </v-col>

                                <v-col cols="12" sm="6">
                                    <v-text-field v-model="newUser.password"
                                        :rules="isEditMode ? [] : checkEmptyRule('Password is required')" name="password"
                                        label="Password" type="password" placeholder="password" required></v-text-field>

                                </v-col>



                                <v-col cols="12" sm="6" class="d-flex flex-column justify-center">
                                    <v-checkbox label="student" hide-details :rules="roleRules" value="student"
                                        v-model="newUser.roles">
                                    </v-checkbox>
                                    <v-checkbox label="instructor" hide-details :rules="roleRules" value="instructor"
                                        v-model="newUser.roles">
                                    </v-checkbox>
                                    <v-checkbox label="admin" hide-details :rules="roleRules" value="admin"
                                        v-model="newUser.roles">
                                    </v-checkbox>
                                </v-col>
                                <v-col cols="12" sm="6">
                                    <v-radio-group v-model="newUser.is_active" row>
                                        <v-radio label="Active" :value="1"></v-radio>
                                        <v-radio label="Inactive" :value="0"></v-radio>
                                    </v-radio-group>
                                    <!-- <v-text-field v-model="newCategory.is_active" label="Status"></v-text-field> -->
                                </v-col>
                            </v-row>
                        </v-form>

                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="userFormDialog = false">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="save()">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-container>
            <v-alert v-model="showError" dense dismissible type="error" elevation="1">
                Error
            </v-alert>
            <v-data-table class=" elevation-1" :headers="headers" :items="Users" :items-per-page="5">

                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>Users</v-toolbar-title>
                        <!-- <v-divider class="mx-4" inset vertical></v-divider> -->
                        <v-spacer></v-spacer>

                        <v-btn color="primary" @click="openDialogForEdit(null)">
                            ADD User
                        </v-btn>

                    </v-toolbar>
                </template>

                <template v-slot:item.roles="{ item }">
                    <!-- <span v-for="(role, i) in item.roles" :key="i">{{ role.name }} </span> -->
                    <v-chip class="ma-2" color="primary" v-for="role in item.roles" :key="role.id">
                        {{ role.name }}
                    </v-chip>
                </template>

                <template v-slot:item.is_active="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'" dark>
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                    </v-chip>
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="openDialogForEdit(item)">
                        mdi-pencil
                    </v-icon>

                    <v-icon class="ml-2" small @click="delUserBtn(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-container>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";



export default {

    components: {

    },
    data: () => ({
        showError: false,
        headers: [
            {
                text: 'id',
                value: 'id',
            },
            { text: 'First Name', value: 'first_name' },
            { text: 'Last Name', value: 'last_name' },
            { text: 'Email', value: 'email' },
            { text: 'Roles', value: 'roles', sortable: false },
            { text: 'Status', value: 'is_active', sortable: false },
            { text: 'Actions', value: 'actions', sortable: false },
        ],
        delDialog: false,
        newUser: {
            user_id: null,
            first_name: null,
            last_name: null,
            is_active: 0,
            roles: [],
            email: null,
            password: null
        },
        userFormDialog: false,
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],

        isEditMode: false,
    }),

    created() {
        this.GetUsers()
    },

    computed: {
        ...mapGetters(['Users']),

        roleRules() {
            return [
                v => this.newUser.roles.length > 0 || 'Role is required'
            ];
        }
    },

    methods: {
        ...mapActions(['GetUsers', 'SaveUser', 'DeleteUser']),
        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },
        deleteUser() {
            let form = {
                user_id: this.selectedUser.id
            }
            this.DeleteUser(form)

                .then((response) => {
                    let index = this.Users.findIndex(s => s.id == this.selectedUser.id)
                    this.Users.splice(index, 1)
                    this.delDialog = false
                })
        },
        save() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                // console.log('valid');
                this.SaveUser(this.newUser)

                    .then((response) => {
                        this.GetUsers()
                    })
                    .catch(res => {
                        this.showError = true
                    })
                this.userFormDialog = false
            }

        },
        delUserBtn(user) {
            this.selectedUser = user
            this.delDialog = true;
        },
        getRolesForCheckbox(user) {
            return user.roles.map(role => {
                return role.name
            })
        },
        openDialogForEdit(user) {
            if (user) {
                this.newUser.user_id = user.id
                this.newUser.first_name = user.first_name
                this.newUser.last_name = user.last_name
                this.newUser.is_active = user.is_active
                this.newUser.roles = this.getRolesForCheckbox(user)
                this.newUser.email = user.email
                this.newUser.password = null
                this.isEditMode = true
            } else {
                this.newUser = {
                    user_id: null,
                    first_name: null,
                    last_name: null,
                    is_active: 0,
                    roles: ['student'],
                    email: null,
                    password: null
                }
                this.isEditMode = false
            }
            this.userFormDialog = true
        }
    }
}
</script>
