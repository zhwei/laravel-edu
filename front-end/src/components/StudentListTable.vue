<template>
  <el-table
      :data="paginator.items"
      style="width: 100%">
    <el-table-column prop="id" label="ID"></el-table-column>
    <el-table-column prop="school_name" label="学校"></el-table-column>
    <el-table-column prop="name" label="姓名"></el-table-column>
    <el-table-column fixed="right" label="操作">
      <template slot-scope="scope">
        <el-button v-if="onChat"
                   type="info"
                   :round="true"
                   :plain="true"
                   @click="onChat(scope.row)"
                   size="small">发消息
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import {listFollowingApi, listTeachingApi} from "@/api/teacher";

export default {
  name: "StudentListTable",
  props: ['api', 'onChat'],
  data() {
    return {
      paginator: {
        lastId: 0,
        items: [],
      }
    }
  },
  created() {
    this.refreshList()
  },
  methods: {
    refreshList() {
      const method = {
        teaching: listTeachingApi,
        following: listFollowingApi,
      }[this.$props.api]
      method(this.paginator.lastId)
          .then(resp => this.paginator = resp.data)
          .catch(error => this.$message.error(error.response.data.message))
    },
  },
  watch: {
    api: function () {
      this.refreshList()
    }
  },
}
</script>

<style scoped>

</style>
