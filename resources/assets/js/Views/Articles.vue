<style></style>

<template>
<v-layout row wrap>
    <v-flex xs12 class="text-xs-center" v-if="articles.length <= 0 && !loading">
    	<h1>No articles here!</h1>
    </v-flex>
    <v-flex xs4 v-for="(article, index) in articles" v-else-if="articles.length > 0">
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
                <v-btn flat class="white--text" v-if="hasArticle(article)" v-on:click.native="deleteArticle(article, index)" v-bind:disabled="deleting">
                    Delete
                </v-btn>
                <v-btn flat class="white--text" v-if="hasArticle(article)" v-on:click.native="updateArticle(article)">
                    Update
                </v-btn>
                <v-btn flat class="white--text" v-on:click.native="readArticle(article)">
                    Read
                </v-btn>
            </v-card-row>
        </v-card>
    </v-flex>
    <v-flex xs12 class="text-xs-center" v-if="loading">
    	<v-progress-circular 
		    indeterminate 
		    v-bind:size="50" 
		    class="primary--text" 
		  />
    </v-flex>
    <v-flex xs12 class="text-xs-center" v-if="next_page_url">
    	<v-btn 
		    info
		    v-on:click.native="fetch" 
		    v-bind:disabled="!next_page_url || loading"
		    class="white--text"
		>
		    Load More
		</v-btn>
    </v-flex>
</v-layout>
</template>

<script>
import { mapGetters } from 'vuex'
import { mapActions } from 'vuex'

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
			loading: true,
			deleting: false,
			category: ''
		}
	},
	created () {
        this.fetch()
    },
    computed: {
    	...mapGetters(['hasArticle'])
    },
	methods: {
		...mapActions(['notify']),
		fetch () {
			var query = this.$route.query
            
			this.per_page = query.per || 9
			this.category = query.category || ''

			if (this.last_page > this.current_page) {
				this.current_page += 1
			}

			this.loading = true
			this.$http.get('/api/articles?page=' + this.current_page + '&per=' + this.per_page + '&category=' + this.category).then((res) => {
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
				if (!err.success) {
					this.notify({ msg: err.msg, show: true })
				}
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.loading = false
			})
		},
		deleteArticle (article, index) {
			if (!article.link) {
				return
			}
			this.deleting = true
			this.$http.delete('/api/articles/' + article.link).then((res) => {
				var data = res.body

				if (data.success) {
			        this.articles.splice(index, 1)
					this.notify({ msg: data.msg, show: true })
				}
			}).catch((err) => {
				if (!err.success) {
					this.notify({ msg: err.msg, show: true })
				}
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.deleting = false
			})
		},
		updateArticle (article) {
			if (!article.link) {
				return
			}
			this.$router.push({ name: 'updateArticle', params: { link: article.link } })
		},
		readArticle (article) {
			if (!article.link) {
				return
			}
			this.$router.push({ name: 'article', params: { link: article.link } })
		}
	},
	watch: {
		'$route': 'fetch'
	}
}
</script>