<template>
  <panel title="我关注的">
    <student-teachers-table type="following"></student-teachers-table>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import StudentTeachersTable from "@/components/StudentTeachersTable";

export default {
  name: "StudentFollowing",
  components: {StudentTeachersTable, Panel},
}
</script>

<style scoped>

</style>
