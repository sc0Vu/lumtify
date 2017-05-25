<style></style>

<template>
<v-col xs4="xs4">
    <div>
        <div>
            <h5>Register</h5>
        </div>
        <div>
	        <v-alert v-bind:info="!info" v-bind:error="!success" v-bind:value="true" v-if="msg">
			    {{ msg }}
			</v-alert>
		</div>
		<div>
		    <v-text-field 
			    name="name"
			    label="Name"
			    type="text"
			    v-model="name"
			    dark
			    prepend-icon="account_box"
			></v-text-field>
			<span class="red--text" v-if="errFor.name">{{ errFor.name.join(",") }}</span>
		</div>
        <div>
		    <v-text-field 
			    name="email"
			    label="Email"
			    type="email"
			    v-model="email"
			    dark
			    prepend-icon="email"
			></v-text-field>
			<span class="red--text" v-if="errFor.email">{{ errFor.email.join(",") }}</span>
		</div>
        <div>
		    <v-text-field 
			    name="pass"
			    label="Password"
			    type="password"
			    v-model="password"
			    dark
			    prepend-icon="lock"
			></v-text-field>
			<span class="red--text" v-if="errFor.password">{{ errFor.password.join(",") }}</span>
		</div>
		<div>
		    <v-text-field 
			    name="pass_verify"
			    label="Password Again"
			    type="password"
			    v-model="password_verify"
			    dark
			    prepend-icon="lock"
			></v-text-field>
			<span class="red--text" v-if="errFor.pass_verify">{{ errFor.pass_verify.join(",") }}</span>
		</div>
        <div>
            <v-btn 
                info
                v-bind:disabled="loading"
                v-on:click.native="register"
                small
            >
                Register
            </v-btn>
        </div>
    </div>
</v-col>
</template>

<script>
import { mapActions } from 'vuex'

export default {
	data () {
		return {
			name: '',
			email: '',
			password: '',
			password_verify: '',
			errFor: {},
			success: false,
			loading: false,
			msg: '',
		}
	},
	methods: {
		...mapActions(['notify']),
		register () {
			this.loading = true
			this.$http.post('/api/users', {
				name: this.name,
				email: this.email,
				pass: this.password,
				pass_verify: this.password_verify,
			}).then((res) => {
				var data = res.body

				if (data.success) {
					this.errFor = data.errFor
					this.errs = data.errs
					this.msg = data.msg
					this.success = data.success
					this.notify({ msg: data.msg, show: true })
					this.$router.push({ name: 'home' })
				}
			}).catch((err) => {
				var e = err.body

				if (!e.success) {
					this.errFor = e.errFor
					this.errs = e.errs
					this.msg = e.msg
					this.success = e.success
					this.notify({ msg: e.msg, show: true })
				} else {
					this.$router.push({ name: 'home' })
				}
			}).then(() => {
				this.loading = false
			})
		}
	}
}
</script>