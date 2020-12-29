<template>
  <el-row type="flex" justify="center" class="login">
    <el-col :xs="24" :sm="18" :md="10" :lg="8" :xl="6">
      <el-card>
        <h2>Login</h2>
        <el-form :model="form" :rules="formRules" ref="form">
          <el-form-item label="邮箱" prop="email">
            <el-input v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item label="密码" prop="password">
            <el-input v-model="form.password" type="password"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onLogin">登陆</el-button>
            <el-button>取消</el-button>
          </el-form-item>
        </el-form>

        <router-link to="/register" class="el-link el-link--primary">注册</router-link>
      </el-card>

    </el-col>
  </el-row>
</template>

<script>
import axios from 'axios'
import rules from './rules'
import {login} from "@/utils/auth";

export default {
  name: "Login",
  data: () => {
    return {
      form: {
        email: '',
        password: '',
      },
      formRules: {
        email: rules.email,
        password: rules.password,
      },
    }
  },
  methods: {
    onLogin() {
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          return false;
        }

        axios.post('http://127.0.0.1:8000/auth/login', this.form)
            .then(resp => {
              login(resp.data.access_token, resp.data.expires_at)
              this.$message.success('登陆成功')
            })
            .catch(error => {
              this.$message.error(error.response.data.message)
            })

      });
    },
  }
}
</script>

<style scoped>
.login {
  margin-top: 50px;
}
</style>
