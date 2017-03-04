<style></style>

<template>
<v-toolbar>
    <v-toolbar-title>
        <h4 class="white--text">Lumtify</h4>
    </v-toolbar-title>
    <v-toolbar-items>
        <v-toolbar-item href="/" ripple router>Home</v-toolbar-item>
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
                    <v-list-tile href="/register" ripple router>
                        <v-list-tile-title>Register</v-list-tile-title>
                    </v-list-tile>
                </v-list-item>

                <v-list-item v-else-if="auth.isAuth">
                    <v-list-tile v-if="roles(['admin', 'editor'])" v-bind:href="{name: 'createArticle'}" ripple router>
                        <v-list-tile-title>Write</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile v-if="roles(['admin'])">
                        <v-list-tile-title>Users</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile v-bind:href="{name: 'profile', params: {uid: auth.user.uid}}" ripple router>
                        <v-list-tile-title>Profile</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile v-on:click.native="logout">
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
            isAuth: false,
            user: {},
            roles: []
        }
    },
    methods: {
        logout () {
            this.$http.get('/api/auth/logout').then((res) => {
                var data = res.body

                if (data.success) {
                    localStorage.setItem('lumtify', '')
                    alert(data.msg)
                    this.$router.push({ name: 'home' })
                }
            }).catch((err) => {
                var e = err.body

                if (!e.success) {
                    console.log(e)
                }
            }).then(() => {
            })
        },
        roles (roles) {
            if (this.auth.roles.length === 0) {
                return false
            }
            var length = roles.length

            for (var i=0; i<length; i++) {
                if (this.auth.roles.indexOf(roles[i]) >= 0) {
                    return true
                }
            }
            return false
        }
    }
}
</script>