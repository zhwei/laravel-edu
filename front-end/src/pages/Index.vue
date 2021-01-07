<template>
  <panel>
    <div style="text-align: center">
      <p> 请关注公众号，以免漏收通知</p>
      <p>
        <img v-if="isProduction" src="@/assets/line-032ypqyi.png" alt="qr code">
        <img v-else src="@/assets/line-340wysuj.png" alt="qr code">
      </p>
      <p style="color: #9e9e9e;">
        Created by <a style="color: #9a9a9a;" href="https://github.com/zhwei" target="_blank">@zhwei</a>
      </p>
    </div>
  </panel>
</template>

<script>
import Panel from "@/components/Panel";

export default {
  name: "Index",
  components: {
    Panel,
  },
  data() {
    return {
      isProduction: process.env.NODE_ENV === 'production'
    }
  },
  methods: {},
}
</script>

<style scoped>

</style>
