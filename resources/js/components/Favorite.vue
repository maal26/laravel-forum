<template>
    <button class="btn btn-sm btn-outline-primary" @click="toggle">
        {{ favoritesCount | plural }}
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
            }
        },
        filters: {
            plural(value) {
                return value === 1 ? `${value} Favorite` : `${value} Favorites`;
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
