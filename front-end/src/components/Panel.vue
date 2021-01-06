<template>
  <div>
    <el-row>
      <el-col :xs="6" :sm="8" :md="6" :lg="4" :xl="4">
        <el-menu :router="true">
          <el-submenu index="username">
            <template slot="title">
              <span><strong>{{ user.name }}</strong></span>
            </template>
            <el-menu-item-group>
              <el-menu-item>{{ user.role.toUpperCase() }}</el-menu-item>
              <el-menu-item @click="onLogout">退出登陆</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-menu-item-group
              v-for="menu in menus"
              :key="menu.name"
              :index="menu.name">
            <template slot="title">
              <i :class="menu.icon"></i> {{ menu.name }}
            </template>
            <el-menu-item v-for="child in menu.children"
                          :index="child.path"
                          :key="menu.name + '-' + child.name">
              {{ child.name }}
            </el-menu-item>
          </el-menu-item-group>
        </el-menu>
      </el-col>

      <el-col :xs="18" :sm="16" :md="18" :lg="20" :xl="20">
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

    <el-drawer
        title="我是标题"
        :visible="talkOpen"
        direction="rtl">
      <span>我来啦!</span>
    </el-drawer>
  </div>
</template>

<script>
import {getUserInfo, logout} from "@/utils/auth";
import {ALL_MENUS} from "@/menu";

export default {
  name: "Panel",
  props: [
    'title',
    // 聊天
    'talkTitle',
    'talkOpen',
  ],
  data() {
    return {
      user: {},
      menus: [],
    }
  },
  created() {
    this.user = getUserInfo()

    for (const menu of ALL_MENUS) {
      if (menu.roles.includes(this.user.role)) {
        this.menus.push(menu)
      }
    }
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
