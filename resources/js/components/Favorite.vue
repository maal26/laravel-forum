<template>
    <button class="btn btn-sm btn-outline-primary" @click="toggle">
        <i class="text-danger" :class="favoriteClass"></i>
        {{ favoritesCount }}
    </button>
</template>

<script>
    export default {
        props: {
            reply: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                isFavorited: this.reply.isFavorited,
                favoritesCount: this.reply.favoritesCount
            }
        },
        computed: {
            endpoint() {
                return `/replies/${this.reply.id}/favorites`;
            },
            favoriteClass() {
                return this.isFavorited ? 'fas fa-heart' :'far fa-heart';
            }
        },
        methods: {
            toggle() {
                return this.isFavorited ? this.unfavorite() : this.favorite();
            },
            favorite() {
                axios.post(this.endpoint);
                this.isFavorited = true;
                this.favoritesCount++;
            },
            unfavorite() {
                axios.delete(this.endpoint);
                this.isFavorited = false;
                this.favoritesCount--;
            }
        }
    }
</script>
