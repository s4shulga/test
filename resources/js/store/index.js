import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    state: {
        profiles: [],
        profile: {},
        isReady: false,
    },
    getters: {
        getItems: state =>{
            return state.profiles
        },
        getProfile: state =>{
            return state.profile
        },
    },
    actions: {
        async createProfile( {commit}, {data}) {
            console.log(data);
            const response = await axios.post(`/api/v1/profiles`, data);
            commit('createProfile', response.data.data);
        },
        async updateProfile({ commit }, {profileId, data }) {
            const response = await axios.post(`/api/v1/profiles/${profileId}`, data);
            commit('updateProfile', response.data.data);
        },
        async getProfileById({ commit }, profileId){
            return commit('getProfileById', await axios.get(`/api/v1/profiles/${profileId}`))
        },
        async getAllProfiles({ commit }){
            return commit('setProfiles', await axios.get('/api/v1/profiles'))
        },
        async deleteProfileById( {commit}, profileId) {
            return commit('deleteProfileById', await axios.delete(`/api/v1/profiles/${profileId}`))
        },
        async deleteExistingImage( {commit}, imageId) {
          return commit('deleteExistingImage', await axios.delete(`/api/v1/images/${imageId}`))
        }
    },

    mutations: {
        deleteProfileById(state, response) {
            if(response.data.data.id) {
                const index = state.profiles.findIndex(item => item.id === response.data.data.id);
                if (index < 0) {
                    return;
                }
                state.profiles.splice(index, 1);
            }
        },
        deleteExistingImage(state, response,) {
            console.log(state, response);
            if(response.data.data.id) {
                const index = state.profile.profile_images.findIndex(item => item.id === response.data.data.id);
                if (index < 0) {
                    return;
                }
                state.profile.profile_images.splice(index, 1);
            }
        },
        getProfileById(state, response) {
          state.profile = response.data.data;
        },
        setProfiles(state, response) {
            state.profiles = response.data.data;
            state.isReady = true;
        },
        updateProfile(state, profile) {
            const index = state.profiles.findIndex(item => item.id === profile.id);
            if (index < 0) {
                return;
            }
            state.profiles[index] = profile;
            state.profile = profile;
        },
        createProfile(state, response) {
            state.profile = response.data.data;
        }
    },
    strict:debug
});

