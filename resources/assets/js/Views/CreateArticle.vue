<style></style>

<template>
<v-col xs4="xs4">
    <div>
        <div>
            <h5>Create Article</h5>
        </div>
        <div>
	        <v-alert v-bind:info="!info" v-bind:error="!success" v-bind:value="true" v-if="msg">
			    {{ msg }}
			</v-alert>
		</div>
		<div>
		    <v-text-field 
			    name="title"
			    label="Title"
			    type="text"
			    v-model="article.title"
			></v-text-field>
			<span class="red--text" v-if="errFor.title">{{ errFor.title.join(",") }}</span>
		</div>
        <div>
		    <v-text-field 
			    name="link"
			    label="Link"
			    type="text"
			    v-model="article.link"
			></v-text-field>
			<span class="red--text" v-if="errFor.link">{{ errFor.link.join(",") }}</span>
		</div>
        <div>
		    <v-text-field 
			    name="short_description"
			    label="Short Description"
			    type="text"
			    v-model="article.short_description"
			></v-text-field>
			<span class="red--text" v-if="errFor.short_description">{{ errFor.short_description.join(",") }}</span>
		</div>
		<v-row>
	    	<v-col xs6="xs6">
	    	    <v-textarea
	                name="content"
		            label="Content"
	                v-model="article.content"
	            ></v-textarea>
		    </v-col>
		    <v-col xs6="xs6">
		        <div>
		            <h6>Preview</h6>
		            <p v-markdown="article.content"></p>
		        </div>
		    </v-col>
			<span class="red--text" v-if="errFor.content">{{ errFor.content.join(",") }}</span>
		</v-row>
		<div>
		    <v-text-field 
			    name="thumbnail"
			    label="Thumbnail"
			    type="text"
			    v-model="article.thumbnail"
			></v-text-field>
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
                v-on:click.native="create"
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
	data () {
		return {
			article: {
				link: '',
				title: '',
				short_description: '',
				content: '',
				thumbnail: '',
				status: 1
			},
			errFor: {},
			success: false,
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
	methods: {
		create () {
			this.sending = true
			this.$http.post('/api/articles', this.article).then((res) => {
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
	}
}
</script>