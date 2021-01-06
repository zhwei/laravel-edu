<template>
  <div>
    <panel title="我关注的">
      <student-teachers-table type="following" :on-chat="onChat"></student-teachers-table>
    </panel>

    <chat :open="chatOpen"
          :teacher="chatTeacher"
          :student="chatStudent"
          @close="chatOpen = false"></chat>
  </div>
</template>

<script>
import Panel from "@/components/Panel";
import StudentTeachersTable from "@/components/StudentTeachersTable";
import {getUserInfo} from "@/utils/auth";
import Chat from "@/components/Chat";

export default {
  name: "StudentFollowing",
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
