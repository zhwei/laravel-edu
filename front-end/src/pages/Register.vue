<template>
  <el-row type="flex" justify="center" class="login">
    <el-col :xs="24" :sm="18" :md="10" :lg="8" :xl="6">
      <el-card>
        <h2>老师注册</h2>
        <el-form :model="form" :rules="formRules" ref="form">
          <el-form-item label="邮箱" prop="email">
            <el-input v-model="form.email"></el-input>
          </el-form-item>
          <el-form-item label="姓名" prop="name">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="密码" prop="password">
            <el-input v-model="form.password" type="password"></el-input>
          </el-form-item>
          <el-form-item label="再次输入密码" prop="password_confirmation">
            <el-input v-model="form.password_confirmation" type="password"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">注册</el-button>
            <el-button @click="$router.push('/login')">已有用户登陆</el-button>
          </el-form-item>
        </el-form>
      </el-card>

    </el-col>
  </el-row>
</template>

<script>
import _ from 'lodash'
import {login} from "@/utils/auth";
import {registerApi} from "@/api/auth";
import rules from "@/utils/rules";

export default {
  name: "Register",
  data() {
    const checkPassword = (rule, value, callback) => {
      if (this.form.password !== value) {
        callback(new Error('两次输入的密码不一致'))
      } else {
        callback()
      }
    }

    return {
      form: {
        email: '',
        name: '',
        password: '',
        password_confirmation: '',
      },
      formRules: {
        email: rules.email,
        name: rules.name,
        password: rules.password,
        password_confirmation: [
          ...rules.password,
          {message: '两次输入的密码不一致', validator: checkPassword}
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          alert('error')
          return false;
        }

        registerApi(this.form)
            .then(resp => {
              login(resp.data)
              this.$message.success('注册成功')
              this.$router.push('/')
            })
            .catch(error => {
              if (!_.isEmpty(error.response.data.errors)) {
                for (const errors of Object.values(error.response.data.errors)) {
                  for (const msg of errors) {
                    this.$message.error(msg)
                  }
                }
              } else if (!_.isEmpty(error.response.data)) {
                this.$message.error(error.response.data.message)
              }
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
