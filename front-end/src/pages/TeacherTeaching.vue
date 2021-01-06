<template>
  <div>
    <panel title="我的学生">
      <student-list-table api="teaching" :on-chat="onChat"></student-list-table>
    </panel>

    <chat :open="chatOpen"
          :teacher="chatTeacher"
          :student="chatStudent"
          @close="chatOpen = false"></chat>
  </div>
</template>

<script>
import Panel from "@/components/Panel";
import StudentListTable from "@/components/StudentListTable";
import Chat from "@/components/Chat";
import {getUserInfo} from "@/utils/auth";

export default {
  name: "TeacherTeaching",
  components: {Chat, StudentListTable, Panel},
  data() {
    return {
      chatOpen: false,
      chatTeacher: getUserInfo(),
      chatStudent: null,
    }
  },
  methods: {
    onChat(student) {
      this.chatStudent = student
      this.chatOpen = true
    },
    onClose() {
      this.chatOpen = false
    }
  }
}
</script>

<style scoped>

</style>
