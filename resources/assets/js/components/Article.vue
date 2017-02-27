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
    <v-col xs12="xs12" v-if="article">
	    <h1>{{ article.title }}</h1>
	    <h3>{{ article.short_description }}</h3>
	    <h6>Create: {{ article.created_at }}, Update: {{ article.updated_at }}</h6>
	    <p>{{ article.content }}</p>
	    <v-card>
	        <v-card-row class="blue darken-1">
	            <v-card-title>
	                <span class="white--text">{{ article.author.name }}</span>
	                <v-spacer></v-spacer>
	            </v-card-title>
	        </v-card-row>
	        <v-card-row actions class="blue darken-1 mt-0">
	            <v-card-text class="blue darken-1 white--text">
		            <div v-text="card_text">{{ article.author.uid }}</div>
		        </v-card-text>
	        </v-card-row>
	    </v-card>
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
			article: null,
			loading: true
		}
	},
	created () {
        this.fetch()
    },
	methods: {
		fetch () {
			this.loading = true
			this.$http.get('/api/articles/' + this.$route.params.link).then((res) => {
				var data = res.body

				if (data.success) {
					this.article = data.article
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