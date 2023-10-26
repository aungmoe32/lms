<template>
    <div class="mt-5">
        <!-- <h1 class="subheading grey--text px-5">Categories</h1> -->
        <v-container>
            <v-data-table class=" elevation-1" :headers="headers" :items="Categories" :items-per-page="5">

                <template v-slot:top>
                    <v-toolbar flat>
                        <v-toolbar-title>Categories</v-toolbar-title>
                        <!-- <v-divider class="mx-4" inset vertical></v-divider> -->
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogCreate" max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                                    ADD
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">Category</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-form ref="form"  lazy-validation>
                                            <v-row>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-text-field v-model="newCategory.name"
                                                        :rules="checkEmptyRule('Name is required')"
                                                        label="Name"></v-text-field>
                                                </v-col>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-text-field v-model="newCategory.icon_class" label="Icon Class"
                                                        :rules="checkEmptyRule('Icon is required')"></v-text-field>
                                                        <span>Example:fa-user | Use <a href="https://fontawesome.com/v5/search" target="_blank">Font Awesome</a> icons</span>

                                                    </v-col>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-radio-group v-model="newCategory.is_active" row>
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
                                    <v-btn color="blue darken-1" text @click="cancel">
                                        Cancel
                                    </v-btn>
                                    <v-btn color="blue darken-1" text @click="save">
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <!-- <v-dialog v-model="dialogDelete" max-width="500px">
                            <v-card>
                                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                    <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog> -->
                    </v-toolbar>
                </template>

                <template v-slot:item.icon="{ item }">
                    <i :class="'fa ' + item.icon_class + ' my_icon'"></i>
                </template>

                <template v-slot:item.status="{ item }">
                    <v-chip :color="item.is_active ? 'green' : 'red'" dark>
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                    </v-chip>
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="openDialogForEdit(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="deleteCategory(item)">
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
    name: 'Category',
    components: {

    },
    data: () => ({
        headers: [
            {
                text: 'id',
                value: 'id',
            },
            { text: 'Icon', value: 'icon' },
            { text: 'Name', value: 'name' },
            { text: 'Status', value: 'status' },
            { text: 'Actions', value: 'actions', sortable: false },
        ],
        newCategory: {
            category_id: null,
            name: null,
            icon_class: null,
            is_active: 0,
        },
        dialogCreate: false,
    }),

    created() {
        this.GetCategories()
    },

    computed: {
        ...mapGetters(['Categories']),
    },

    methods: {
        ...mapActions(['GetCategories', 'SaveCategories', 'DeleteCategory']),
        checkEmptyRule(msg) {
            return [
                v => !!v || msg,
            ]
        },
        save() {
            let isvalid = this.$refs.form.validate()
            if (isvalid) {
                this.SaveCategories(this.newCategory)
                    .then((response) => {
                        // console.log('save success');
                        // this.GetCategories()
                    })
                    .catch(error => {
                        // console.log(error);
                    })
                this.dialogCreate = false
                this.resetNewCategory()
            }

        },
        cancel() {
            this.resetNewCategory()
            this.dialogCreate = false
        },

        resetNewCategory() {
            this.newCategory.category_id = null
            this.newCategory.name = null
            this.newCategory.icon_class = null
            this.newCategory.is_active = 0
        },

        deleteCategory(category) {

            let form = { category_id: category.id }
            this.DeleteCategory(form)
                .catch((error) => { })
            this.dialogCreate = false
        },
        openDialogForEdit(category) {
            this.newCategory.category_id = category.id;
            this.newCategory.name = category.name;
            this.newCategory.icon_class = category.icon_class;
            this.newCategory.is_active = category.is_active;

            this.dialogCreate = true
        },
    }
}
</script>

<style >
/* .v-data-table-header{
        background-color: blue;
    } */
</style>
