<template>
  <el-row>
    <el-col :xs="6" :sm="8" :md="8" :lg="4" :xl="4">
<!--      <p>{{ user.name }}</p>-->
<!--      <a @click="logout">退出登陆</a>-->

      <el-menu
          default-active="2"
          class="el-menu-vertical-demo">
        <el-submenu index="0">
          <template slot="title">
            <span><strong>{{ user.name }}</strong></span>
          </template>
          <el-menu-item-group>
            <el-menu-item index="0-1" @click="onLogout">退出登陆</el-menu-item>
          </el-menu-item-group>
        </el-submenu>
        <el-menu-item index="2">
        </el-menu-item>
        <el-submenu index="1">
          <template slot="title">
            <i class="el-icon-user-solid"></i>
            <span>学生</span>
          </template>
          <el-menu-item-group>
            <template slot="title">分组一</template>
            <el-menu-item index="1-1">选项1</el-menu-item>
            <el-menu-item index="1-2">选项2</el-menu-item>
          </el-menu-item-group>
          <el-menu-item-group title="分组2">
            <el-menu-item index="1-3">选项3</el-menu-item>
          </el-menu-item-group>
          <el-submenu index="1-4">
            <template slot="title">选项4</template>
            <el-menu-item index="1-4-1">选项1</el-menu-item>
          </el-submenu>
        </el-submenu>
        <el-menu-item index="2">
          <i class="el-icon-s-custom"></i>
          <span slot="title">老师</span>
        </el-menu-item>
        <el-menu-item index="3">
          <i class="el-icon-school"></i>
          <span slot="title">学校管理员</span>
        </el-menu-item>
        <el-menu-item index="4">
          <i class="el-icon-cpu"></i>
          <span slot="title">系统管理员</span>
        </el-menu-item>
      </el-menu>
    </el-col>

    <el-col :span="16">
      <el-breadcrumb separator="/" class="breadcrumb">
        <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
        <el-breadcrumb-item v-if="title">{{ title }}</el-breadcrumb-item>
      </el-breadcrumb>

      <el-divider></el-divider>

      <div class="panel-content">
        <slot></slot>
      </div>

    </el-col>
  </el-row>
</template>

<script>
import {getUserInfo, logout} from "@/utils/auth";

export default {
  name: "Panel",
  props: ['title'],
  data() {
    return {
      user: {},
    }
  },
  created() {
    this.user = getUserInfo()
  },
  methods: {
    onLogout() {
      logout()
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
.breadcrumb {
  padding: 20px;
}

.panel-content {
  padding: 10px;
}
</style>
