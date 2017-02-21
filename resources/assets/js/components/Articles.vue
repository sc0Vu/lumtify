<style></style>

<template>
<div>
<v-card v-for="article in articles">{{ article.title }}
    <!-- <v-card-row class="blue-grey darken-1">
    <v-card-title>
      <span class="white--text">{{ article.title }}</span>
      <v-spacer></v-spacer>
      <v-menu id="space" bottom left origin="top right" transition="v-scale-transition">
        <v-btn icon="icon" slot="activator" class="white--text">
          <v-icon>more_vert</v-icon>
        </v-btn>
        <v-list>
          <v-list-item>
            <v-list-tile>
              <v-list-tile-title>Remove Card</v-list-tile-title>
            </v-list-tile>
          </v-list-item>
          <v-list-item>
            <v-list-tile>
              <v-list-tile-title>Send Feedback</v-list-tile-title>
            </v-list-tile>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-card-title>
  </v-card-row>
  <v-card-row img="/public/doc-images/cards/space.jpg" height="300px"></v-card-row>
  <v-card-text class="blue-grey darken-3 white--text">
    <div v-text="card_text"></div>
  </v-card-text>
  <v-card-row actions class="blue-grey darken-1 mt-0">
    <v-btn flat class="white--text">Get Started</v-btn>
    <v-spacer></v-spacer>
    <v-btn icon>
      <v-icon class="white--text">explore</v-icon>
    </v-btn>
  </v-card-row> -->
</v-card>
</div>
</template>

<script>
export default {
	name: 'articles',
	data () {
		return {
			articles: [],
			total: 0,
			per_page: 10,
			current_page: 1,
			last_page: 3,
			next_page_url: "",
			prev_page_url: null,
			from: 0,
			to: 0,
		}
	},
	method: {
		fetch () {
			this.$http.get(`/api/articles?page={this.current_page}&per={this.per_page}`).then((res) => {
				if (res.success) {
					this.total = res.articles.total
			        this.per_page = res.articles.per_page
			        this.current_page = res.articles.current_page
			        this.last_page = res.articles.last_page
			        this.next_page_url = res.articles.next_page_url
			        this.prev_page_url = res.articles.prev_page_url
			        this.from = res.articles.from
			        this.to = res.articles.to
			        this.articles = res.articles.data
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