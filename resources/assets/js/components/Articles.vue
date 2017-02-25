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
    <v-col xs4="xs4" v-for="article in articles">
        <v-card style="margin-bottom: 15px;">
            <v-card-row class="blue-grey darken-1">
                <v-card-title>
                    <span class="white--text">{{ article.title }}</span>
                    <v-spacer></v-spacer>
                </v-card-title>
            </v-card-row>
            <v-card-row v-bind:img="article.thumbnail" height="300px"></v-card-row>
            <v-card-text class="blue-grey darken-3 white--text">
                <div v-text="card_text">{{ article.short_description }}</div>
            </v-card-text>
            <v-card-row actions class="blue-grey darken-1 mt-0">
                <v-btn flat class="white--text">
                    <router-link v-bind:to="{ name: 'article', params: { link: article.link } }" class="white--text">Read</router-link>
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
	name: 'articles',
	data () {
		return {
			articles: [],
			total: 0,
			per_page: 9,
			current_page: 1,
			last_page: 0,
			next_page_url: null,
			prev_page_url: null,
			from: 0,
			to: 0,
			loading: true
		}
	},
	created () {
        this.fetch()
    },
	methods: {
		fetch () {
			if (this.last_page > this.current_page) {
				this.current_page += 1
			}

			this.loading = true
			this.$http.get('/api/articles?page=' + this.current_page + '&per=' + this.per_page).then((res) => {
				var data = res.body

				if (data.success) {
					this.total = data.articles.total
			        this.per_page = data.articles.per_page
			        this.current_page = data.articles.current_page
			        this.last_page = data.articles.last_page
			        this.next_page_url = data.articles.next_page_url
			        this.prev_page_url = data.articles.prev_page_url
			        this.from = data.articles.from
			        this.to = data.articles.to
			        this.articles = this.articles.concat(data.articles.data)
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