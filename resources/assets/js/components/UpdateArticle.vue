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
            <h5>Update Article {{ article.title }}</h5>
        </div>
        <div>
	        <v-alert v-bind:info="!info" v-bind:error="!success" v-bind:value="true" v-if="msg">
			    {{ msg }}
			</v-alert>
		</div>
		<div>
		    <v-text-input 
			    name="title"
			    label="Title"
			    type="text"
			    v-model="article.title"
			></v-text-input>
			<span class="red--text" v-if="errFor.title">{{ errFor.title.join(",") }}</span>
		</div>
        <div>
		    <v-text-input 
			    name="link"
			    label="Link"
			    type="text"
			    v-model="article.link"
			></v-text-input>
			<span class="red--text" v-if="errFor.link">{{ errFor.link.join(",") }}</span>
		</div>
        <div>
		    <v-text-input 
			    name="short_description"
			    label="Short Description"
			    type="text"
			    v-model="article.short_description"
			></v-text-input>
			<span class="red--text" v-if="errFor.short_description">{{ errFor.short_description.join(",") }}</span>
		</div>
		<div>
		    <v-text-input 
			    name="content"
			    label="Content"
			    type="text"
			    v-model="article.content"
			></v-text-input>
			<span class="red--text" v-if="errFor.content">{{ errFor.content.join(",") }}</span>
		</div>
		<div>
		    <v-text-input 
			    name="thumbnail"
			    label="Thumbnail"
			    type="text"
			    v-model="article.thumbnail"
			></v-text-input>
			<span class="red--text" v-if="errFor.thumbnail">{{ errFor.thumbnail.join(",") }}</span>
		</div>
		<div>
		    <v-select 
		        v-bind:options="statusList"
			    name="status"
			    label="Status"
			    v-model="article.status"
			></v-select>
			<span class="red--text" v-if="errFor.status">{{ errFor.status.join(",") }}</span>
		</div>
        <div>
            <v-btn 
                info
                v-bind:disabled="sending"
                v-on:click.native="update"
                small
            >
                Submit
            </v-btn>
        </div>
    </div>
</v-col>
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
			article: {},
			errFor: {},
			success: false,
			loading: false,
			sending: false,
			msg: '',
			statusList: [
			    {
			    	value: 1,
			    	text: 'Draft'
			    }, {
			    	value: 2,
			    	text: 'Publish'
			    }, {
			    	value: 3,
			    	text: 'Archieve'
			    }
			]
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
				this.$router.push({ name: 'home' })
			}).then(() => {
				this.loading = false
			})
		},
		update () {
			this.sending = true
			this.$http.put('/api/articles/' + this.$route.params.link, this.article).then((res) => {
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
					alert(data.msg)
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