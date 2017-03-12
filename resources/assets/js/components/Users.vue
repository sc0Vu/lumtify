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
    <v-col xs4="xs4" v-for="(user, index) in users">
        <v-card style="margin-bottom: 15px;">
            <v-card-row class="blue-grey darken-1">
                <v-card-title>
                    <span class="white--text">{{ user.name }}</span>
                    <v-spacer></v-spacer>
                </v-card-title>
            </v-card-row>
            <v-card-row v-bind:img="user.thumbnail" height="300px"></v-card-row>
            <v-card-text class="blue-grey darken-3 white--text">
                <div v-text="card_text">
                    <p>Name: {{ user.name }}</p>
                    <p>Email: {{ user.email }}</p>
                </div>
            </v-card-text>
            <v-card-row actions class="blue-grey darken-1 mt-0">
                <v-btn flat class="white--text" v-if="isAdmin" v-on:click.native="deleteUser(user, index)" v-bind:disabled="deleting">
                    Delete
                </v-btn>
                <v-btn flat class="white--text" v-if="isAdmin" v-on:click.native="updateUser(user)">
                    Update
                </v-btn>
                <v-btn flat class="white--text" v-on:click.native="readUser(user)">
                    Read
                </v-btn>
            </v-card-row>
        </v-card>
    </v-col>
    <v-col xs12="xs12" class="text-xs-center" v-if="next_page_url">
    	<v-btn 
		    info
		    v-on:click.native="fetch" 
		    v-bind:disabled="!next_page_url || loading"
		    class="white--text"
		>
		    Load More
		</v-btn>
    </v-col>
</v-row>
</template>

<script>
export default {
	name: 'users',
	props: {
        auth: {
            isAuth: false,
            user: {},
            roles: []
        }
    },
	data () {
		return {
			users: [],
			total: 0,
			per_page: 9,
			current_page: 1,
			last_page: 0,
			next_page_url: null,
			prev_page_url: null,
			from: 0,
			to: 0,
			loading: true,
			deleting: false,
			isAdmin: false
		}
	},
	created () {
        this.fetch()
        this.isAdmin = this.checkAdmin()
    },
	methods: {
		fetch () {
			if (this.last_page > this.current_page) {
				this.current_page += 1
			}

			this.loading = true
			this.$http.get('/api/users?page=' + this.current_page + '&per=' + this.per_page).then((res) => {
				var data = res.body

				if (data.success) {
					this.total = data.users.total
			        this.per_page = data.users.per_page
			        this.current_page = data.users.current_page
			        this.last_page = data.users.last_page
			        this.next_page_url = data.users.next_page_url
			        this.prev_page_url = data.users.prev_page_url
			        this.from = data.users.from
			        this.to = data.users.to
			        this.users = this.users.concat(data.users.data)
				}
			}).catch((err) => {
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.loading = false
			})
		},
		checkAdmin () {
			var roles = this.auth.roles

			if (roles.indexOf("admin") >= 0) {
				return true
			}
			return false
		},
		deleteUser (user, index) {
			if (!user.uid) {
				return
			}
			this.deleting = true
			this.$http.delete('/api/users/' + user.uid).then((res) => {
				var data = res.body

				if (data.success) {
			        this.users.splice(index, 1)
			        alert(data.msg)
				}
			}).catch((err) => {
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.deleting = false
			})
		},
		updateUser (user) {
			if (!user.uid) {
				return
			}
			this.$router.push({ name: 'setting', params: { uid: user.uid } })
		},
		readUser (user) {
			if (!user.uid) {
				return
			}
			this.$router.push({ name: 'profile', params: { uid: user.uid } })
		}
	},
	watch: {
		'$route': 'fetch'
	}
}
</script>