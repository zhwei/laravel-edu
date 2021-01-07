<template>
  <el-row type="flex" justify="center" class="login">
    <el-col :xs="24" :sm="18" :md="10" :lg="8" :xl="6">
      <el-card>
        <h2>用哪个身份登陆？</h2>
        <p v-if="loading">加载中 ...</p>
        <p v-else-if="users.length === 0">
          当前 line 账号尚未绑定用户，请使用邮箱密码登陆成功后，点击菜单中的【绑定 Line】进行绑定
        </p>
        <div v-else>
          <p v-for="user in users" :key="user.id">
            <el-button @click="onLogin(user.id)">姓名：{{ user.name }}，角色：{{ user.role }}</el-button>
          </p>
        </div>
        <div v-if="currentUser && false === currentUser.lineBinded && showBind">
          <el-divider></el-divider>
          <el-button type="primary"
                     @click="onBind">
            绑定当前账号
          </el-button>
        </div>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import {authedApi, unAuthedApi} from "@/api/api";
import {getUserInfo, login} from "@/utils/auth";

export default {
  name: "LineLogin",
  data() {
    return {
      title: '',
      token: '',
      users: [],
      loading: true,
      currentUser: null,
      showBind: true,
    }
  },
  created() {
    const url = new URL(location.href)
    this.token = url.searchParams.get('token')
    if (!this.token) {
      this.$message.error('非法请求')
    }

    this.currentUser = getUserInfo()

    this.refreshUserList()
  },
  methods: {
    refreshUserList() {
      unAuthedApi().post('/auth/line/users', {token: this.token})
          .then(resp => {
            this.users = resp.data
            this.loading = false
          })
          .catch(error => this.$message.error(error.response.data.message))
    },
    onLogin(userId) {
      unAuthedApi().post('/auth/line/login', {token: this.token, userId})
          .then(resp => {
            login(resp.data)
            this.$message.success('登陆成功')
            location.href = '?'
          })
          .catch(error => {
            this.$message.error(error.response.data.message)
          })
    },
    onBind() {
      authedApi()
          .post('/line/bind', {token: this.token})
          .then(() => {
            this.$message.success('绑定成功')
            this.refreshUserList()
            this.showBind = false
          })
          .catch(error => {
            this.$message.error(error.response.data.message)
          })
    }
  }
}
</script>

<style scoped>

</style>
