<template>
    <div>
        <div v-for="reply in items" :key="reply.id">
            <reply :reply="reply" @deleted="filterReplies"></reply>
        </div>

        <new-reply :endpoint="endpoint" @created="addReply"></new-reply>
    </div>
</template>

<script>
import Reply from './Reply';
import NewReply from './NewReply';
export default {
    props: {
        replies: {
            type: Array,
            required: true
        }
    },
    components: {
        NewReply,
        Reply
    },
    data() {
        return {
            items: this.replies,
        }
    },
    computed: {
        endpoint() {
            return `${window.location.pathname}/replies`;
        }
    },
    methods: {
        addReply(reply) {
            this.items.push(reply);

            this.$emit('created');
        },
        filterReplies({ id }) {
            this.items = this.items.filter(i => i.id !== id);

            this.$emit('removed');
        }
    }
}
</script>

<style>

</style>
