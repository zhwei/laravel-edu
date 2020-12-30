<template>
  <panel title="学校信息">
    <div v-if="school">
      <p>学校名称：{{ school.name }}</p>
    </div>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import {getSchoolInfo} from "@/api/student";

export default {
  name: "StudentInfo",
  components: {Panel},
  data() {
    return {
      school: {},
    }
  },
  created() {
    getSchoolInfo()
        .then(resp => this.school = resp.data)
        .catch(error => this.$message.error(error.response.data.message))
  }
}
</script>

<style scoped>

</style>
