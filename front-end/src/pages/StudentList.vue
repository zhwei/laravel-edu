<template>
  <panel title="学生列表">
    <student-list-table :api="type"></student-list-table>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import StudentListTable from "@/components/StudentListTable";

export default {
  name: "StudentList",
  components: {StudentListTable, Panel},
  props: ['type'],
  data() {
    return {}
  },
  created() {
  },
  methods: {},
}
</script>

<style scoped>

</style>
