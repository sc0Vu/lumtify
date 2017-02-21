<style></style>

<template>
<div v-if="article">
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
        <v-card-text class="blue darken-1 white--text">
            <div v-text="card_text">{{ article.author.uid }}</div>
        </v-card-text>
        <v-card-row actions class="blue darken-1 mt-0">
            <v-btn icon>
                <v-icon class="white--text">explore</v-icon>
            </v-btn>
        </v-card-row>
    </v-card>
</div>
</template>

<script>
export default {
	data () {
		return {
			article: null,
		}
	},
	created () {
        this.fetch()
    },
	methods: {
		fetch () {
			this.$http.get('/api/articles/' + this.$route.params.link).then((res) => {
				var data = res.body

				if (data.success) {
					this.article = data.article
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