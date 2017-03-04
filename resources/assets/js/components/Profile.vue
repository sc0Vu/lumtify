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
    <v-col xs12="xs12" v-else-if="!loading">
	    <h1>
	        {{ name }}
		</h1>
	    <h3>{{ email }}</h3>
	    <v-row>
	    	<v-btn class="white--text" primary small v-on:click.native="setting">
	    	    Setting
            </v-btn>
	    </v-row>
    </v-col>
</v-row>
</template>

<script>
export default {
	props: {
        auth: {
            isAuth: false,
            user: {},
            roles: []
        }
    },
	data () {
		return {
			name: '',
			email: '',
			uid: '',
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
					this.uid = data.user.uid
					this.name = data.user.name
					this.email = data.user.email
				}
			}).catch((err) => {
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.loading = false
			})
		},
		setting () {
			if (!this.uid) {
				return
			}
			this.$router.push({name: 'setting', params: {uid: this.uid}})
		}
	},
	watch: {
		'$route': 'fetch'
	}
}
</script>