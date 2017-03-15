<style></style>

<template>
<v-col xs4="xs4">
    <v-col xs12="xs12" class="text-xs-center" v-if="loading">
    	<v-progress-circular 
		    indeterminate 
		    v-bind:size="50" 
		    class="primary--text" 
		  />
    </v-col>
    <div v-else-if="!loading">
        <div>
            <h5>Setting</h5>
        </div>
        <div>
	        <v-alert v-bind:info="!info" v-bind:error="!success" v-bind:value="true" v-if="msg">
			    {{ msg }}
			</v-alert>
		</div>
		<div>
		    <v-text-input 
			    name="name"
			    label="Name"
			    type="text"
			    v-model="name"
			></v-text-input>
			<span class="red--text" v-if="errFor.name">{{ errFor.name.join(",") }}</span>
		</div>
        <div>
		    <v-text-input 
			    name="email"
			    label="Email"
			    type="email"
			    v-model="email"
			></v-text-input>
			<span class="red--text" v-if="errFor.email">{{ errFor.email.join(",") }}</span>
		</div>
		<div>
		    <v-text-input 
			    name="thumbnail"
			    label="Thumbnail"
			    type="text"
			    v-model="thumbnail"
			></v-text-input>
			<span class="red--text" v-if="errFor.thumbnail">{{ errFor.thumbnail.join(",") }}</span>
		</div>
        <div>
		    <v-text-input 
			    name="pass"
			    label="Password"
			    type="password"
			    v-model="password"
			></v-text-input>
			<span class="red--text" v-if="errFor.password">{{ errFor.password.join(",") }}</span>
		</div>
		<div>
		    <v-text-input 
			    name="pass_verify"
			    label="Password Again"
			    type="password"
			    v-model="password_verify"
			></v-text-input>
			<span class="red--text" v-if="errFor.pass_verify">{{ errFor.pass_verify.join(",") }}</span>
		</div>
		<div v-if="hasRoles(['admin']) && rolesList.length > 0">
			<v-select 
		        v-bind:options="rolesList"
			    name="roles"
			    label="Roles"
			    v-model="roles"
			    multiple
			></v-select>
			<span class="red--text" v-if="errFor.roles">{{ errFor.roles.join(",") }}</span>
		</div>
        <div>
            <v-btn 
                primary
                v-bind:disabled="sending"
                v-on:click.native="setting"
                small
            >
                Submit
            </v-btn>
        </div>
    </div>
</v-col>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
	data () {
		return {
			name: '',
			email: '',
			thumbnail: '',
			password: '',
			password_verify: '',
			errFor: {},
			success: false,
			loading: false,
			sending: false,
			msg: '',
			rolesList: [],
			roles: []
		}
	},
	created () {
		this.fetch()
	},
	mounted () {
		if (this.hasRoles(['admin'])) {
			this.fetchRoles()
		}
	},
	computed: {
		...mapGetters(['hasRoles'])
	},
	methods: {
		fetch () {
			this.loading = true
			this.$http.get('/api/users/' + this.$route.params.uid).then((res) => {
				var data = res.body

				if (data.success) {
					this.name = data.user.name
					this.email = data.user.email
					this.thumbnail = data.user.thumbnail
					this.roles = data.roles
				}
			}).catch((err) => {
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.loading = false
			})
		},
		fetchRoles () {
			this.$http.get('/api/roles').then((res) => {
				var data = res.body

				if (data.success) {
					var self = this

					data.roles.forEach(function (role) {
						self.rolesList.push({value: role, text: role})
					})
				}
			}).catch((err) => {
				// this.$router.push({ name: 'home' })
			}).then(() => {
			})
		},
		setting () {
			var user = {}

			if (this.name) {
				user.name = this.name
			}
			if (this.email) {
				user.email = this.email
			}
			if (this.thumbnail) {
				user.thumbnail = this.thumbnail
			}
			if (this.password) {
				user.pass = this.password
			}
			if (this.password_verify) {
				user.pass_verify = this.password_verify
			}
			if (this.roles) {
				user.roles = this.roles
			}
			this.sending = true
			this.$http.put('/api/users/' + this.$route.params.uid, user).then((res) => {
				var data = res.body

				if (data.success) {
					this.errFor = data.errFor
					this.errs = data.errs
					this.msg = data.msg
					this.success = data.success
					alert(data.msg)
					this.$router.push({ name: 'home' })
				}
			}).catch((err) => {
				var e = err.body

				if (!e.success) {
					this.errFor = e.errFor
					this.errs = e.errs
					this.msg = e.msg
					this.success = e.success
					alert(e.msg)
				} else {
					this.$router.push({ name: 'home' })
				}
			}).then(() => {
				this.sending = false
			})
		}
	},
	watch: {
		'$route': 'fetch'
	}
}
</script>