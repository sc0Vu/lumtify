<style></style>

<template>
<v-row>
    <v-col xs12="xs12" class="text-xs-center">
    	<h1>Not Found</h1>
    </v-col>
</v-row>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name: 'notfound',
    data () {
    	return {
    		msg: 'Sorry! This page cannot be found:('
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
