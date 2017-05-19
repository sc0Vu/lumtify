<style></style>

<template>
<div>
    <h1>About Lumtify</h1>
    <div>
        <p>Lumen + Vuetify blog</p>
        <p>A blog cms build with lumen(jwt-api) and vuetify.</p>
        <p>Author: <a href="https://sc0vu.github.io/">Peter Lai</a></p>
        <p>
            <a href="https://github.com/sc0Vu/lumtify">Github</a>
        </p>
    </div>
</div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name: 'about',
    data () {
    	return {
    		msg: 'Hello! Nice to meet you:!'
    	}
    },
    mounted () {
		this.notify({ msg: this.msg, show: true })
    },
    methods: {
    	...mapActions(['notify'])
    }
}
</script>
