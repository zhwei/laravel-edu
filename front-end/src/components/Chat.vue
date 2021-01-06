<template>
  <div>
    <el-drawer
        title="聊天"
        :visible.sync="open"
        direction="rtl"
        :before-close="handleClose">
      <p v-for="(msg, idx) in messages" :key="idx">
        <span v-if="msg.send">发：</span>
        <span v-if="msg.receive">收：</span>
        {{ msg.content }}
      </p>
    </el-drawer>
  </div>
</template>

<script>
import {createEcho} from "@/utils/echo";

export default {
  name: "Chat",
  props: [
    'channel'
  ],
  data() {
    return {
      open: false,
      echo: null,
      messages: [],
    }
  },
  created() {
    this.echo = createEcho()
  },
  watch: {
    channel: function (newValue, oldValue) {
      console.log([oldValue, newValue])
      if (newValue !== oldValue) {
        this.echo.leave(oldValue)
      }

      if (newValue) {
        this.echo
            .private(this.channel)
            .listen('sent', message => this.messages.push({message, send: true}))
            .listen('receive', message => this.messages.push({message, receive: true}))
        this.open = true
      }
    }
  },
  methods: {
    handleClose() {
      this.open = false
    },
  },
  computed: {

  },
}
</script>

<style scoped>

</style>
