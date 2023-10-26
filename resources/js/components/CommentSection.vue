<template>
    <v-row class="my-5">
        <!-- <v-col class="col-12">
            <h3>Comments</h3>
        </v-col> -->
        <v-col class="col-12" v-if="isAuthenticated">
            <v-form class="d-flex ">
                <v-textarea label="Write your Comment" v-model="form.content" auto-grow outlined rows="3"
                    row-height="25"></v-textarea>
                <v-btn x-large icon :disabled="form.content == ''" @click="send()" color="primary">
                    <v-icon>mdi-send</v-icon>
                </v-btn>
            </v-form>
        </v-col>
        <v-col class="col-12">
            <h3>Lastest Comments</h3>
            <v-list three-line>
                <template v-for="(item, index) in Comments">



                    <v-list-item :key="item.id">
                        <v-list-item-avatar>
                            <v-img src="/imgs/profile.png"></v-img>
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>
                                <h4 v-if="isAuthenticated && StateUser.id == item.user.id" class="primary--text">{{ item.user.first_name + ' ' + item.user.last_name }} (You)</h4>
                                <h4 v-else class="primary--text">{{ item.user.first_name + ' ' + item.user.last_name }}</h4>
                            </v-list-item-title>

                            <p style="white-space: pre-line;">{{ item.content }}</p>

                            <div>
                                <v-subheader class="float-right">{{ JoinOn(item) }}</v-subheader>
                            </div>

                        </v-list-item-content>


                    </v-list-item>

                    <v-divider :key="'A'+item.id" ></v-divider>
                </template>
            </v-list>
            <v-btn color="primary" v-if="Comments.length == 7 " class="my-3" @click="GetAllComments(course_id);">Show all comments</v-btn>
        </v-col>
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex";


export default {
    props: ['course_id'],
    data() {
        return {
            form: {
                content: '',
                course_id: this.course_id,

            },
            show_cmt_btn: true
        }
    },
    created() {
        this.GetLastestComments(this.course_id)
    },
    computed: {
        ...mapGetters(['Comments','AllComments', 'JoinOn', 'StateUser', 'isAuthenticated']),

    },

    methods: {
        ...mapActions(["GetLastestComments", "SaveComment", "GetAllComments"]),

        send() {
            this.SaveComment(this.form)
            this.form.content = ''
        }
    }
}
</script>
