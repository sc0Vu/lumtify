<style></style>

<template>
<v-row>
    <v-col xs12="xs12" class="text-xs-center" v-if="loading">
    	<v-progress-circular 
		    indeterminate 
		    v-bind:size="50" 
		    class="primary--text" 
		  />
    </v-col>
    <v-col xs12="xs12" v-if="user">
	    <h1>{{ user.name }}</h1>
	    <h3>{{ user.email }}</h3>
    </v-col>
</v-row>
</template>

<script>
export default {
	data () {
		return {
			user: null,
			loading: true
		}
	},
	created () {
        this.fetch()
    },
	methods: {
		fetch () {
			this.loading = true
			this.$http.get('/api/users/' + this.$route.params.uid).then((res) => {
				var data = res.body

				if (data.success) {
					this.user = data.user
				}
			}).catch((err) => {
				console.log(err)
			}).then(() => {
				this.loading = false
			})
		}
	},
	watch: {
		'$route': 'fetch'
	}
}
</script>