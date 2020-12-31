<template>
  <panel title="关注我的">
    <student-list-table api="following"></student-list-table>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import StudentListTable from "@/components/StudentListTable";

export default {
  name: "TeacherFollowing",
  components: {StudentListTable, Panel},
}
</script>

<style scoped>

</style>
