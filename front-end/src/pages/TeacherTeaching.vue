<template>
  <panel title="我的学生">
    <student-list-table api="teaching"></student-list-table>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import StudentListTable from "@/components/StudentListTable";

export default {
  name: "TeacherTeaching",
  components: {StudentListTable, Panel}
}
</script>

<style scoped>

</style>
