<template>
  <div>
    <panel title="老师们">
      <student-teachers-table
          type="teaching"
          :on-chat="onChat"
      ></student-teachers-table>

      <chat :open="chatOpen"
            :teacher="chatTeacher"
            :student="chatStudent"
            @close="chatOpen = false"></chat>
    </panel>
  </div>
</template>

<script>
import Panel from "@/components/Panel";
import StudentTeachersTable from "@/components/StudentTeachersTable";
import {getUserInfo} from "@/utils/auth";
import Chat from "@/components/Chat";

export default {
  name: "StudentTeachers",
  components: {Chat, StudentTeachersTable, Panel},
  data() {
    return {
      chatOpen: false,
      chatTeacher: null,
      chatStudent: getUserInfo(),
    }
  },
  methods: {
    onChat(teacher) {
      this.chatTeacher = teacher
      this.chatOpen = true
    },
    onClose() {
      this.chatOpen = false
    }
  },
}
</script>

<style scoped>
</style>
