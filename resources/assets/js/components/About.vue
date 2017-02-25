<style></style>

<template>
<div>
    <h1>About Lumtify</h1>
    <div>
        <p>Lumen + Vuetify blog</p>
        <p>A blog cms build with lumen(jwt-api) and vuetify.</p>
        <p>Author: Peter Lai</p>
        <p><a href="https://github.com/sc0Vu/lumtify">Github</a></p>
    </div>
</div>
</template>

<script>
export default {
	props: {
        auth: {
            isAuth: false,
            user: {}
        }
    },
}
</script>