<template>
  <el-table
      :data="paginator.items"
      style="width: 100%">
    <el-table-column prop="name" label="老师"></el-table-column>
    <el-table-column fixed="right" label="操作">
      <template slot-scope="scope">
        <el-button v-if="scope.row.following"
                   @click="onUnfollow(scope.row)"
                   type="danger"
                   :round="true"
                   :plain="true"
                   size="small">取消关注
        </el-button>
        <el-button v-else
                   @click="onFollow(scope.row)"
                   type="primary"
                   :round="true"
                   :plain="true"
                   size="small">关注
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import {followApi, getFollowingTeachers, getSchoolTeachers, unfollowApi} from "@/api/student";

export default {
  name: "StudentTeachersTable",
  props: ['type'],
  data() {
    return {
      paginator: {lastId: 0, items: []}
    }
  },
  created() {
    this.refreshList()
  },
  methods: {
    refreshList() {
      const api = {
        teaching: getSchoolTeachers,
        following: getFollowingTeachers,
      }[this.$props.type]
      api()
          .then(resp => this.paginator = resp.data)
          .catch(error => this.$message.error(error.response.data.message))
    },
    onFollow(teacher) {
      followApi(teacher.id)
          .then(() => this.refreshList())
          .catch(error => this.$message.error(error.response.data.message))
    },
    onUnfollow(teacher) {
      unfollowApi(teacher.id)
          .then(() => this.refreshList())
          .catch(error => this.$message.error(error.response.data.message))
    },
  },
}
</script>

<style scoped>

</style>
