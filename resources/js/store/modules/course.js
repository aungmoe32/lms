import axios from "axios";

const state = {
    lastestCourses: [],
    // curriculums: {
    //     sections: []
    // },


    myCourses : [],

    learningCourse: null,
    learningLecture: null,

};

const getters = {
    LastestCourses: (state) => state.lastestCourses,

    LearningCourse: (state) => state.learningCourse,
    LearningLecture: (state) => state.learningLecture,
    MyCourses: (state) => state.myCourses,
};

const actions = {

    GetLastestCourses({ state, commit }) {
        let path = '/lastest-courses'

        return axios.get(path)
            .then((response) => {
                commit('setLastestCourses', response.data.lastestCourses)
                return response
            })
            .catch(error => {

            })
    },

    GetFilteredCourses({ state, commit }, params) {
        let path = '/courses?' + params

        return axios.get(path)
    },


    GetCourseViewInfo({ state, commit }, id) {
        let path = '/course-view/' + id

        return axios.get(path)
    },

    IsEnroll({ state, commit }, course_id) {

        return axios.get('is-enroll/' + course_id)
    },


    GetMyCourses({ state, commit }) {
        let path = '/student/my-courses'

        return axios.get(path)
        .then(res=> {
            state.myCourses = res.data
        }).catch(err => {
            console.log(err);
        })
    },

    GetLearningCourse({ state, commit }, course_id) {
        let path = '/course-enroll/' + course_id

        return axios.get(path)
        .then(res => {
            state.learningCourse = res.data
        })
        .catch(err => {

        })
    },

    GetLearningLecture({ state, commit }, form) {
        let path = '/course-enroll-lecture/'+ form.course_id + '/' + form.lecture_id

        return axios.get(path)
        .then(res => {
            state.learningLecture = res.data
        })
        .catch(err => {

        })
    },


    UpdateLectureStatus({ state, commit }, form) {
        let path = '/update-lecture-status/' + form.course_id + '/' + form.lecture_id + '/' + form.status

        return axios.get(path)
        .then(res => {
        })
        .catch(err => {

        })
    },









    UploadFile({ state, commit }, { path , form }) {
        // let p = '/instructor/courses/lecturevideo/save/' + form.lecture_id
        // let path = p + form.lecture_id

        let formData = new FormData();

        for (let key in form) {
            formData.append(key, form[key]);
        }

        return axios.post(path, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

    },


};

const mutations = {

    setLastestCourses(state, lastestCourses) {
        state.lastestCourses = lastestCourses;
    },


    setLearningCourse(state, course) {
        state.learningCourse = course;
    },

};

export default {
    state,
    getters,
    actions,
    mutations,
};
