<template>
<div>
    <page-header></page-header>
    <main>
        <v-content>
            <v-container>
                <transition appear>
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-content>
    </main>
    <v-snackbar 
        :timeout="timeout"
        :top="y === 'top'"
        :bottom="y === 'bottomm'"
        :right="x === 'right'"
        :left="x === 'left'"
        v-model="show"
        v-on:input="snakerChange"
    >
    {{ msg }}
      <v-btn icon="icon" flat class="pink--text" @click.native="setShow(false)">
          <v-icon>close</v-icon>
      </v-btn>
    </v-snackbar>
</div>
</template>

<script>
import PageHeader from './PageHeader.vue'
import { mapGetters } from 'vuex'
import { mapMutations } from 'vuex'

export default {
	name: 'page-lumtify',
	components: {
		PageHeader
	},
    computed: {
        ...mapGetters(['x', 'y', 'show', 'msg', 'timeout']),
    },
    methods: {
        ...mapMutations(['setShow']),
        snakerChange (val) {
            // due to value doesn't when timeout
            // so we listen input event and change value
            if (val !== this.show) {
                this.setShow(val)
            }
        }
    },
    mounted () {
        console.log('%c%s', 'color: blue; font-size:72px;', 'Hi! Welcome to lumtify!')
    }
}
</script>