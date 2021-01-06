<template>
  <el-row type="flex" justify="center" class="login">
    <el-col :xs="24" :sm="18" :md="10" :lg="8" :xl="6">
      <el-card>
        <h2>点击想要登陆的用户身份</h2>
        <p v-for="user in users" :key="user.id">
          <el-button @click="onLogin(user.id)">姓名：{{ user.name }}，角色：{{ user.role }}</el-button>
        </p>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import {unAuthedApi} from "@/api/api";
import {login} from "@/utils/auth";

export default {
  name: "LineLogin",
  data() {
    return {
      token: '',
      users: [],
    }
  },
  created() {
    const url = new URL(location.href)
    this.token = url.searchParams.get('token')
    if (!this.token) {
      this.$message.error('非法请求')
    }

    unAuthedApi().post('/auth/line/users', {token: this.token})
        .then(resp => this.users = resp.data)
        .catch(error => this.$message.error(error.response.data.message))
  },
  methods: {
    onLogin(userId) {
      unAuthedApi().post('/auth/line/login', {token: this.token, userId})
          .then(resp => {
            login(resp.data)
            this.$message.success('登陆成功')
            this.$router.push('/')
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
