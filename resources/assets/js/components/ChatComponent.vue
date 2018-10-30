<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-header">Private Chat App</div>
                    <ul class="list-group" >
                        <li class="list-group-item friend" @click.prevent="openChat(user)" v-for="user in friends" :key="user.id">
                            <a href=""
                               >{{user.name}}
                                <span class="badge badge-secondary"
                                      v-if="user.session && user.session.unreadCount > 0">{{user.session.unreadCount}}</span>
                                <i class="fa fa-circle text-success float-right"
                                   v-if="user.online"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div v-for="user in friends" :key="user.id" v-if="user.session">
                   <message-component v-if="user.session.open"  @close="close(user)" :friend="user" ></message-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MessageComponent from './MessageComponent'

    export default {
        data: function(){
            return{
                friends: []
            }
        },
        components: {
          MessageComponent
        },
        created(){
            this.getFriends();
            Echo.channel('Chat')
            .listen('SessionEvent', (e) => {
                let friend = this.friends.find(friend => friend.id == e.session_by);
                friend.session = e.session;
                this.listenEverySession(friend);
            });
            Echo.join('Chat')
            .here((users) => {
                this.friends.forEach( friend =>{
                    users.forEach(user => {
                        if(user.id == friend.id) {
                            friend.online = true;
                        }
                    })
                })
            })
            .joining((user) => {
                this.friends.forEach(
                    friend => (user.id == friend.id ? (friend.online = true) : '')
                )
            })
            .leaving((user) => {
                this.friends.forEach(
                    friend => (user.id == friend.id ? (friend.online = false) : '')
                )
            });
        },
        mounted() {

        },
        methods: {
            close(user){
                user.session.open = false;
            },
            getFriends(){
                axios.post('/get-friends')
                    .then((response) => {
                        this.friends = response.data.data;
                        this.friends.forEach(user => (user.session ? this.listenEverySession(user) : ''))
                    });
            },
            listenEverySession(user){
                Echo.private(`Chat.${user.session.id}`)
                .listen('PrivateChatEvent', 
                    (e) => (user.session.open ? '' : user.session.unreadCount++)
                )
            },
            openChat(user){
                if(user.session){
                    this.friends.forEach(el => el.session ? (el.session.open = false) : '');
                    user.session.open = true;
                    user.session.unreadCount = 0;
                } else {
                    this.createSession(user);
                }
            },
            createSession(user){
                axios.post('/session/create', {'user_id': user.id})
                    .then(res => {
                        (user.session = res.data.data),
                        (user.session.open = true);
                    })
            },
            
        }
    }
</script>

<style>
    .friend{
        padding: 0;
    }
    .friend a{
        padding: 15px 20px;
        margin: 0;
        display: block;
        width: 100%;
        height: 100%;
    }
    .friend a:hover{
        background: #ccc;
    }
</style>