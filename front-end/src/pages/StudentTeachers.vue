<template>
  <panel title="老师们">
    <student-teachers-table
        type="teaching"
        :on-chat="onChat"
    ></student-teachers-table>

    <el-drawer
        title="聊天"
        :visible.sync="chatOpen"
        direction="rtl"
        :before-close="onChatClose">
      <div class="messages">
        <p v-for="(msg, idx) in chatMessages" :key="idx">
          <strong>{{ msg.name }}({{ msg.id }})：</strong>
          {{ msg.content }}
        </p>
        <span id="message-end"></span>
      </div>
      <div class="input" v-loading="chatLoading">
        <el-input type="textarea" :rows="2" placeholder="" v-model="chatInput"></el-input>
        <el-button class="submit" @click="onChatSend">发送</el-button>
      </div>
    </el-drawer>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import StudentTeachersTable from "@/components/StudentTeachersTable";
import {createEcho} from "@/utils/echo";
import {getUserInfo} from "@/utils/auth";
import {studentTalk} from "@/api/message";

export default {
  name: "StudentTeachers",
  components: {StudentTeachersTable, Panel},
  data() {
    return {
      echo: null,
      chatOpen: false,
      chatLoading: true,
      chatChannel: '',
      chatInput: '',
      chatTarget: null,
      chatMessages: [],
    }
  },
  methods: {
    onChat(teacher) {
      const that = this
      if (!this.echo) {
        this.echo = createEcho()
      }

      const user = getUserInfo()
      this.chatTarget = teacher
      this.chatChannel = `room.${user.id}.${teacher.id}`
      this.echo
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
      studentTalk(this.chatTarget.id, this.chatInput)
          .then(() => {
            this.$message.success('发送成功')
            this.chatInput = ''
          })
          .catch(error => this.$message.error(error.response.data.message))
    },
    onChatClose() {
      this.echo.leave(this.chatChannel)
      this.chatChannel = ''
      this.chatMessages.splice(0)
      this.chatOpen = false
      this.chatLoading = true
    },
    scrollToEnd() {
      this.$nextTick(() => {
        this.$el.querySelector('#message-end').scrollIntoView()
      })
    }
  },
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
