<style></style>

<template>
<v-toolbar>
    <v-toolbar-title>
        <h4 class="white--text">Lumtify - Lumen + Vuetify Blog</h4>
    </v-toolbar-title>
    <v-toolbar-items>
        <v-toolbar-item href="/about" ripple router>About</v-toolbar-item>
        <v-menu bottom origin="top right" transition="v-scale-transition">
            <v-btn dark icon slot="activator">
                <v-icon>more_vert</v-icon>
            </v-btn>
            <v-list>
                <v-list-item v-if="!auth.isAuth" href="/about" ripple router>
                    <v-list-tile href="/login" ripple router>
                        <v-list-tile-title>Login</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-title>Register</v-list-tile-title>
                    </v-list-tile>
                </v-list-item>

                <v-list-item v-else>
                    <v-list-tile>
                        <v-list-tile-title>Write</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-title>Setting</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-title>Log Out</v-list-tile-title>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-toolbar-items>
</v-toolbar>
</template>

<script>
export default {
	name: 'page-header',
    props: {
        auth: {
            token: '',
            isAuth: false
        }
    },
	// data () {
 //        return {
 //            auth: {
 //                token: '',
 //                isAuth: false
 //            }
 //        }
	// },
    created () {
        console.log(this)
    },
    // methods: {
    //     auth () {}
    // },
    // watch: {
    //     '$route': 'auth'
    // }
}
</script>