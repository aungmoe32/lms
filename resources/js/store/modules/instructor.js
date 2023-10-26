import axios from "axios";

const state = {
    instructor: null,
    instruction_levels : null,
    courses : []
};

const getters = {
    Instructor: (state) => state.instructor,
    InstructionLevels: (state) => state.instruction_levels,
    Courses: (state) => state.courses,
    CourseImagePath: (state) => (course) => {
        return '/storage/course_images/'+ course.course_image;
    },
    InstructorName: (state) => (instructor) => {
        return instructor.first_name + ' ' + instructor.last_name;
    },
    InstructorImagePath: (state) => (instructor) => {
        return '/storage/instructor_images/'+ instructor.instructor_image;
    }
};

const actions = {

    BecomeInstructor({ state, commit }, form) {
        return axios.post('student/become-instructor', form)
            .then((response) => {
                commit("setInstructor", response.data.instructor);
                commit("setUser", response.data.user);
                return response
            })
    },

    SaveCourse({ state, commit }, form) {
        return axios.post('instructor/course-info-save', form)
    },
    GetInstructor({ state, commit }, id) {
        return axios.get('instructors/'+id)
    },
    GetInstructors({ state, commit }) {
        return axios.get('instructors')
    },
    GetCourse({ state, commit }, course_id) {
        let path = 'instructor/course-info/'
        if(course_id){
            path += course_id
        }

        return axios.get(path)
            .then((response) => {
                // console.log(response);
                commit('setInstructionLevels', response.data.instruction_levels)
                return response
            })
    },

    GetCourseImage({ state, commit }, id) {
        let path = '/course-image/' + id

        return axios.get(path)
    },

    // SaveCourseImage({ state, commit }, form) {
    //     let path = '/instructor/course-image-save'
    //     return axios.post(path, form)
    // },

    GetCourses({ state, commit }) {
        let path = 'instructor/course-list'

        return axios.get(path)
            .then((response) => {
                commit('setCourses', response.data.courses)
                return response
            })
    },
    DeleteCourse({ state, commit }, course_id) {
        let path = 'instructor/course-delete/' + course_id

        return axios.get(path)
            // .then((response) => {
            //     return response
            // }, (error) => {
            //     return Promise.reject(error)
            // })
    },

    GetMetrics({ state, commit }) {
        let path = 'instructor/course-metrics'

        return axios.get(path)
    },


};

const mutations = {
    setInstructor(state, instructor) {
        state.instructor = instructor;
    },


    setInstructionLevels(state, instruction_levels) {
        state.instruction_levels = instruction_levels;
    },
    setCourses(state, courses) {
        state.courses = courses;
    },





};

export default {
    state,
    getters,
    actions,
    mutations,
};
