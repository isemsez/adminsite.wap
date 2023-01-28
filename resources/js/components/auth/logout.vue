<template>

</template>

<script type="application/javascript">
export default {
    created() {
        User.clear()
        Toast.fire({
            icon: "success",
            title: "Вышли из учётной записи"
        })
        this.$router.push({ name: '/' })
    },
}
</script>

<style scoped>

</style>
