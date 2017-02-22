<style></style>

<template>
<v-col xs4="xs4">
    <div>
        <div>
            <h5>Login</h5>
        </div>
        <div>
		    <v-text-input 
			    name="email"
			    label="Email"
			    type="email"
			    v-model="email"
			></v-text-input>
		</div>
        <div>
		    <v-text-input 
			    name="password"
			    label="Password"
			    type="password"
			    v-model="password"
			></v-text-input>
		</div>
        <div>
            <v-btn 
                info
                v-on:click.native="login"
                small
            >
                Login
            </v-btn>
        </div>
    </div>
</v-col>
</template>

<script>
export default {
	data () {
		return {
			email: '',
			password: '',
		}
	},
	methods: {
		login () {
			this.$http.post('/api/auth/login', {
				email: this.email,
				password: this.password
			}).then((res) => {
				var data = res.body

				if (data.success) {
					localStorage.setItem('lumtify', data.token)
				}
			}).catch((err) => {
				console.log(err)
			}).then(() => {
				console.log('Finished')
			})
		}
	},
	watch: {
		'$route': 'fetch'
	}
}
</script>