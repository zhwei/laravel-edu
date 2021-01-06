<template>
  <el-row type="flex" justify="center" class="login">
    <el-col :xs="24" :sm="18" :md="10" :lg="8" :xl="6">
      <el-card>
        <h2>登陆</h2>
        <el-form :model="form" :rules="formRules" ref="form">
          <el-form-item label="邮箱" prop="email">
            <el-input v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item label="密码" prop="password">
            <el-input v-model="form.password" type="password"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onLogin">登陆</el-button>
            <el-button @click="$router.push('/register')">注册新用户</el-button>
            <el-button @click="onLineLogin">Line 登陆</el-button>
          </el-form-item>
        </el-form>
      </el-card>

    </el-col>
  </el-row>
</template>

<script>
import rules from "@/utils/rules";
import {login} from "@/utils/auth"
import {lineLoginUrl, loginApi} from "@/api/auth";

export default {
  name: "Login",
  data: () => {
    return {
      lineUrl: lineLoginUrl(),
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
    onLineLogin() {
      location.href = lineLoginUrl()
    },
    onLogin() {
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          return false;
        }

        loginApi(this.form)
            .then(resp => {
              login(resp.data)
              this.$message.success('登陆成功')
              location.href = '?'
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
