<template>
    <div class="card card-default chat-box" >
        <div class="card-header" >
            <b :class="{'text-danger': session.block}">{{friend.name}}</b> <span v-if="isTyping">is typing ...</span> <span v-if="session.block">(blocked)</span>
            <a href="" class="float-right" @click.prevent="close">
                <i class="fa fa-times"></i>
            </a>
            <div class="dropdown float-right">
                <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" >
                    <i class="fa fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#" @click="block_user" v-if="!session.block">Block</a>
                    <a class="dropdown-item" href="#" @click="unblock_user" v-if="session.block && can">UnBlock</a>
                    <a class="dropdown-item" href="#" @click="clear">Clear chat</a>
                </div>
            </div>
        </div>

        <div class="card-body" v-chat-scroll>
            <ul class="list-group" >
                <li class="list-group-item" v-for="message in messages" :class="{'text-right': message.type == 0}" :key="message.id">
                    <span class="message-back" :class="{'alert-success': message.read_at != null, 'alert-secondary': message.read_at == null}" >
                        {{message.text}}
                        </span>
                </li>
            </ul>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="from-group">
                <input type="text" class="form-control" placeholder="Please, write your message!" v-model="message" :disabled="session.block == true">
                <button type="submit" :disabled="session.block == true">Send</button>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        props: ['friend'],
        data: function(){
          return{
              messages: [],
              message: null,
              isTyping: false
            }
        },
        computed:{
            session(){
                return this.friend.session;
            },
            can(){
                return this.session.blocked_by == auth.id;
            }
        },
        watch:{
            message(value){
                if(value){
                    Echo.private(`Chat.${this.friend.session.id}`).whisper('typing', {
                        name: auth.name
                    });
                }
            }
        },
        methods:{
            send(){
                this.messages.push({text: this.message, type: 0, read_at: null, send_at: 'Just now'});
                axios.post(`/send/${this.friend.session.id}`, {
                    content: this.message,
                    to_user: this.friend.id
                }).then(res => (this.messages[this.messages.length - 1].id = res.data));
                
                this.message = null;
            },
            close(){
                this.$emit('close');
            },
            clear(){
                this.messages = [];
                axios.post(`/session/${this.friend.session.id}/clear`)
                .then(res => this.messages = [])
            },
            block_user(){
                axios.post(`/session/${this.friend.session.id}/block`)
                .then(res => {
                    this.session.block = true;
                    this.session.blocked_by = auth.id;
                })
            },
            unblock_user(){
                axios.post(`/session/${this.friend.session.id}/unblock`)
                .then(res => {
                    this.session.block = false;
                    this.session.blocked = null;
                    })
            },
            getAllMessages(){
                axios.post(`/session/${this.friend.session.id}/chats`)
                .then((res) =>{
                    this.messages = res.data.data;
                })
            },
            read(){
                 axios.post(`/session/${this.friend.session.id}/read`)
            },
        },
        created(){
            this.read();
            this.getAllMessages();
            Echo.private(`Chat.${this.friend.session.id}`).listen('PrivateChatEvent', (e) => {
                this.friend.session.open ? this.read() : '';
                this.messages.push({text: e.content, type: 1, send_at: 'Just now'});
            });

            Echo.private(`Chat.${this.friend.session.id}`).listen('MessageReadEvent', (e) => {
                    this.messages.forEach(message => message.id == e.chat.id ? (message.read_at = e.chat.read_at) :'')
            });

            Echo.private(`Chat.${this.friend.session.id}`).listen('SessionBlockEvent', (e) => {
                    this.session.block = e.blocked
                
            });

            Echo.private(`Chat.${this.friend.session.id}`).listenForWhisper('typing', (e) => {
                this.isTyping = true;
                setTimeout(()=>{
                    this.isTyping = false; 
                }, 2000)
            });

            
            
        },
        
    }
</script>

<style>
    .chat-box{
        height: 500px;
    }
    .card-body{
        overflow-y: scroll;
    }
    a{
        color: #000;
        margin: 5px 10px;
        font-size: 14px;
    }
    a{
        color: #000;
    }
    .fa-times{
        color: red;
    }

    .message-back{
        padding: 10px;
        border-color: #ccc;
        border-radius: 5px;
    }
    .list-group-item{
        border: none;
    }

</style>