<template>
  <audio ref="audio" :src="musicSrc" autoplay loop></audio>
</template>

<script>
export default {
  props: ['musicSrc'],
  mounted() {
    this.$refs.audio.play().catch(() => {}) // untuk autoplay fix
  }
}
</script>
