import conversation from './conversation';
import api from '../api/all';

const state = {
    conversations: [],
    loadedConversations: false

};

const getters = {
    allConversations: state => {
        return state.conversations;
    }
};

const actions = {
    getConversations({dispatch, commit}, page) {
        api.getConversations(1).then((response) => {
            commit('setConversations', response.data.data);
            console.log(response.data);
        });
    }
};

const mutations = {
    setConversations(state, conversations){
        state.conversations = conversations;
    }

};
const modules = {
    conversation: conversation
}

export default {
    state,
    actions,
    getters,
    mutations

}