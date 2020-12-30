<template>
  <panel title="学校列表">
    <el-table
        :data="paginator.items"
        style="width: 100%">
      <el-table-column prop="id" label="ID"></el-table-column>
      <el-table-column prop="name" label="名称"></el-table-column>
      <el-table-column prop="students_count" label="学生数目"></el-table-column>
      <el-table-column prop="approve_at" label="审批通过时间"></el-table-column>
      <el-table-column prop="created_at" label="创建时间"></el-table-column>
      <el-table-column fixed="right" label="操作">
        <template slot-scope="scope">
          <el-button v-if="!scope.row.approve_at"
                     @click="onApprove(scope.row.id)"
                     type="text"
                     size="small">通过
          </el-button>
          <el-button @click="onShowStudentForm(scope.row)"
                     type="text"
                     size="small"> 新增学生
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <!--  抽屉：新增学生 start -->
    <el-drawer
        title="新增学生"
        :visible.sync="showAddStudentForm"
        direction="rtl"
        size="50%">
      <el-form :model="studentForm" :rules="studentFormRules" ref="studentForm" class="drawer-content">
        <el-form-item label="学校" v-if="schoolAddStudent">
          <el-input v-model="schoolAddStudent.name" disabled></el-input>
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="studentForm.email"></el-input>
        </el-form-item>
        <el-form-item label="姓名" prop="name">
          <el-input v-model="studentForm.name"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="studentForm.password" type="password"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmitStudentForm">新增</el-button>
        </el-form-item>
      </el-form>
    </el-drawer>
    <!--  抽屉：新增学生 end  -->


  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import {approveApi, listApi} from "@/api/school";
import rules from "@/utils/rules";
import {createStudentApi} from "@/api/student";

export default {
  name: "SchoolList",
  components: {Panel},
  data() {
    return {
      paginator: {
        lastId: 0,
        items: []
      },
      // 新增学生
      showAddStudentForm: false,
      schoolAddStudent: null,
      studentFormDefaults: {
        email: '',
        name: '',
        password: '',
      },
      studentForm: {
        email: '',
        name: '',
        password: '',
      },
      studentFormRules: {
        email: rules.email,
        name: rules.name,
        password: rules.password,
      },
    }
  },
  created() {
    this.refreshList()
  },
  methods: {

    refreshList() {
      listApi(this.lastId)
          .then(resp => this.paginator = resp.data)
          .catch(error => this.$message.error(error.response.data.message))
    },

    onApprove(schoolId) {
      approveApi(schoolId)
          .then(() => this.$message.success('审批通过') && this.refreshList())
          .catch(error => this.$message.error(error.response.data.message))
    },

    onShowStudentForm(school) {
      this.showAddStudentForm = true
      this.schoolAddStudent = school
    },
    onSubmitStudentForm() {
      this.$refs['studentForm'].validate((valid) => {
        if (!valid) {
          alert('error')
          return false;
        }
        createStudentApi(this.schoolAddStudent.id, this.studentForm)
            .then(() => {
              this.$message.success('新增成功')
              this.refreshList()
              this.showAddStudentForm = false
              this.schoolAddStudent = null
              this.studentForm = this.studentFormDefaults
            })
            .catch(error => this.$message.error(error.response.data.message))
      })
    },

  },
}
</script>

<style scoped>
.drawer-content {
  padding: 20px;
}
</style>
