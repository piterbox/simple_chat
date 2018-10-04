<template>
    <div class="card border-secondary mb-3">
        <div class="card-header">
            Conversation list
        </div>
        <div class="card-body text-secondary">
            <ul class="list-group">
                <li class="list-group-item"
                    v-for="conversation in conversations">
                    <h3 class="card-title">
                        <a href="">{{ conversation.body }}</a>
                    </h3>

                    <p class="card-text">
                        You and {{ conversation.participant_count}} others
                    </p>
                    <ul class="list-inline">
                        <li >

                            <img v-for="user in conversation.users"
                                 src="http://www.gravatar.com/avatar/x?s=45&d=mm"
                                 :alt="user.name">
                       </li>
                        <li>Last reply {{ conversation.last_reply_human }}</li>
                    </ul>
                </li>
           </ul>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'

    export default {
        computed: mapGetters({
            conversations: 'allConversations'
        }),
        methods: {
            ...mapActions([
                'getConversations'
                ])
        },
        mounted() {
            this.getConversations(1);
        }
    }
</script>