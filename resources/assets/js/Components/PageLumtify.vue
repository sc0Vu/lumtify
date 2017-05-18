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
</div>
</template>

<script>
import PageHeader from './PageHeader.vue'

export default {
	name: 'page-lumtify',
	components: {
		PageHeader
	}
}
</script>