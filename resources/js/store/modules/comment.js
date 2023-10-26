import axios from "axios";
import moment from "moment";
const state = {
    comments: [],
};

const getters = {
    Comments: (state) => state.comments,
    JoinOn: (state) => (comment) => {
        return moment(comment.created_at).fromNow()
    },
};

const actions = {

    GetAllComments({ state, commit }, id) {
        return axios.get('all-comments/'+ id)
            .then((response) => {
                commit("setComments", response.data);
            })
    },
    GetLastestComments({ state, commit }, id) {
        return axios.get('comments/'+ id)
            .then((response) => {
                commit("setComments", response.data);
            })
    },

    SaveComment({ state, commit }, form) {
        return axios.post('comment-save', form)
        .then((response) => {
            commit("setComments", response.data);
            return response
        })
    },

};

const mutations = {
    setComments(state, Comments) {
        state.comments = Comments;
    },



};

export default {
    state,
    getters,
    actions,
    mutations,
};
