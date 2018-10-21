<template>
    <div class="card card-default chat-box" >
        <div class="card-header" >
            <b :class="{'text-danger': block}">{{friend.name}}</b> <span v-if="block">(blocked)</span>
            <a href="" class="float-right" @click.prevent="close">
                <i class="fa fa-times"></i>
            </a>
            <div class="dropdown float-right">
                <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" >
                    <i class="fa fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#" @click="block_user" v-if="!block">Block</a>
                    <a class="dropdown-item" href="#" @click="unblock_user" v-else >UnBlock</a>
                    <a class="dropdown-item" href="#" @click="clear">Clear chat</a>
                </div>
            </div>
        </div>

        <div class="card-body" v-chat-scroll>
            <ul class="list-group" >
                <li class="list-group-item" v-for="message in messages" :class="{'text-right': message.type == 0}" :key="message.id">{{message.text}}</li>
            </ul>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="from-group">
                <input type="text" class="form-control" placeholder="Please, write your message!" v-model="message" :disabled="block">
                <button type="submit" :disabled="block">Send</button>
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
              block: false,
          }
        },
        methods:{
            send(){
                this.messages.push({text: this.message, type: 0, send_at: 'Just now'});

                axios.post(`/send/${this.friend.session.id}`, {
                    content: this.message,
                    to_user: this.friend.id
                })
                this.message = null;
            },
            close(){
                this.$emit('close');
            },
            clear(){
                this.messages = [];
            },
            block_user(){
                this.block = true;
            },
            unblock_user(){
                this.block = false;
            },
            getAllMessages(){
                axios.post(`/session/${this.friend.session.id}/chats`)
                .then((res) =>{
                    this.messages = res.data.data;
                })
            },
            read(){
                 axios.post(`/session/${this.friend.session.id}/read`)
            }
        },
        created(){
            this.read();
            this.getAllMessages();
            Echo.private(`Chat.${this.friend.session.id}`).listen('PrivateChatEvent', (e) => {
                this.read();
                this.messages.push({text: e.content, type: 1, send_at: 'Just now'});
            })
        }
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
</style>