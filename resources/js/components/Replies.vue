<template>
    <div>
        <div v-for="reply in items" :key="reply.id">
            <reply :reply="reply" @deleted="filterReplies"></reply>
        </div>
    </div>
</template>

<script>
import Reply from './Reply';
export default {
    props: {
        replies: {
            type: Array,
            required: true
        }
    },
    components: {
        Reply
    },
    data() {
        return {
            items: this.replies,
        }
    },
    watch: {
        message() {
            setTimeout(_ => this.message = '', 2500);
        }
    },
    methods: {
        filterReplies({ id }) {
            this.items = this.items.filter(i => i.id !== id);

            this.$emit('removed');
        }
    }
}
</script>

<style>

</style>
