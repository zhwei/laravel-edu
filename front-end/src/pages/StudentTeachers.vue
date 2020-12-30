<template>
  <panel title="老师们">
    <student-teachers-table type="teaching"></student-teachers-table>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import StudentTeachersTable from "@/components/StudentTeachersTable";

export default {
  name: "StudentTeachers",
  components: {StudentTeachersTable, Panel},
}
</script>

<style scoped>

</style>
