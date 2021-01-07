<template>
  <div id="app">
    <router-view></router-view>

    <el-dialog
        title="系统通知"
        :visible.sync="notifyOpen"
        width="30%">
      <p>{{ notify.content }}</p>
      <p><small>{{ notify.time }}</small></p>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="notifyOpen = false">收到</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {createEcho} from "@/utils/echo";
import {getUserInfo} from "@/utils/auth";

export default {
  name: 'App',
  components: {},
  data() {
    return {
      notifyOpen: false,
      notify: {},
    }
  },
  created() {
    const user = getUserInfo()
    if (user.role === 'student') {
      createEcho()
          .private('students.' + user.id)
          .notification(notify => {
            this.notify = notify
            this.notifyOpen = true
          })
    }
  }
}
</script>

<style>
</style>
