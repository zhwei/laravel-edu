<template>
  <div>
    <el-drawer
        :visible.sync="chatOpen"
        :before-close="onChatClose"
        title="聊天"
        direction="rtl"
        size="40%">
      <div class="messages">
        <p v-for="(msg, idx) in chatMessages" :key="idx">
          <strong>{{ msg.name }}({{ msg.id }})：</strong>
          {{ msg.content }}
        </p>
        <span id="message-end"></span>
      </div>
      <div class="input" v-loading="chatLoading">
        <el-input type="textarea" :rows="3" placeholder="" v-model="chatInput"></el-input>
        <el-button class="submit" @click="onChatSend">发送</el-button>
      </div>
    </el-drawer>
  </div>
</template>

<script>
import {createEcho} from "@/utils/echo";
import {getUserInfo} from "@/utils/auth";
import {studentTalk, teacherTalk} from "@/api/message";

export default {
  name: "Chat",
  props: [
    'open',
    'teacher',
    'student',
  ],
  data() {
    return {
      chatOpen: false,
      chatLoading: true,
      chatChannel: '',
      chatInput: '',
      chatTarget: null,
      chatMessages: [],
    }
  },
  created() {
  },
  watch: {
    open(newValue) {
      this.chatOpen = newValue
      if (newValue) {
        this.onChat()
      }
    },
  },
  methods: {
    onChat() {
      if (!this.teacher || !this.student) {
        return
      }

      const that = this
      this.chatTarget = this.teacher
      this.chatChannel = `room.${this.student.id}.${this.teacher.id}`
      createEcho()
          .private(this.chatChannel)
          .listen('.pusher:subscription_succeeded', () => {
            that.chatLoading = false
          })
          .listen('.chat', message => {
            that.chatMessages.push(message)
            that.scrollToEnd()
          })
      this.chatOpen = true
    },
    onChatSend() {
      let promise
      if (getUserInfo().id === this.teacher.id) {
        promise = teacherTalk(this.student.id, this.chatInput)
      } else {
        promise = studentTalk(this.teacher.id, this.chatInput)
      }
      promise
          .then(() => {
            this.$message.success('发送成功')
            this.chatInput = ''
          })
          .catch(error => this.$message.error(error.response.data.message))
    },
    onChatClose() {
      createEcho().leave(this.chatChannel)
      this.chatChannel = ''
      this.chatMessages.splice(0)
      this.chatOpen = false
      this.chatLoading = true
      this.$emit('close')
    },
    scrollToEnd() {
      this.$nextTick(() => this.$el.querySelector('#message-end').scrollIntoView())
    }
  },
  computed: {},
}
</script>


<style scoped>
.messages {
  height: 70vh;
  position: relative;
  overflow-y: scroll;
  box-sizing: border-box;
  flex: 1;
  width: 100%;
  max-width: 100%;
  word-break: break-all;
  padding: 10px;
}

.input {
  padding: 5px;
}

.submit {
  margin-top: 5px;
}
</style>
