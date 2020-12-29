<template>
  <el-row type="flex" justify="center" class="login">
    <el-col :xs="24" :sm="18" :md="8" :lg="6" :xl="6">
      <el-card>
        <h2>Login</h2>
        <el-form :model="form" :rules="formRules" ref="form">
          <el-form-item label="Email" prop="email">
            <el-input v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item label="Password" prop="password">
            <el-input v-model="form.password" type="password"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onLogin">登陆</el-button>
            <el-button>取消</el-button>
          </el-form-item>
        </el-form>
      </el-card>

    </el-col>
  </el-row>
</template>

<script>
// import {AuthApi} from "@/api/api.ts";

export default {
  name: "Login",
  data: () => {
    return {
      form: {
        email: '',
        password: '',
      },
      formRules: {
        email: [
          {required: true, message: '请输入邮箱地址'},
          {type: 'email', message: '请输入有效邮箱地址'},
        ],
        password: [
          {required: true, message: '请输入密码'},
        ],
      },
    }
  },
  methods: {
    onLogin() {
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          alert('error submit!');
          return false;
        }

        // const api = new AuthApi()
        // const resp = api.login({email: this.form.email, password: this.form.password})
        // console.log(resp)
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
