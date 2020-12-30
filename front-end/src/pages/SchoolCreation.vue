<template>
  <panel title="申请新学校">
    <el-col :lg="12" :xl="12">
      <el-form :model="form" :rules="formRules" ref="form">
        <el-form-item label="学校名称" prop="name">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">申请</el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";
import {createApi} from "@/api/school";

export default {
  name: "SchoolCreation",
  components: {Panel},
  data() {
    return {
      form: {
        name: ''
      },
      formRules: {
        name: {type: 'string', required: true, message: '请输入学校名称'}
      },
    }
  },
  methods: {
    onSubmit() {
      this.$refs['form'].validate((valid) => {
        if (!valid) {
          return false;
        }

        createApi(this.form.name)
            .then(() => {
              this.$message.success('创建成功')
              this.$router.push('/schools/list')
            })
            .catch(error => {
              this.$message.error(error.response.data.message)
            })

      });
    }
  }
}
</script>

<style scoped>

</style>
