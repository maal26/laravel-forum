<template>
    <div>
        <div v-for="reply in replies" :key="reply.id">
            <reply :reply="reply" @deleted="filterReplies"></reply>
        </div>

        <paginator :data-set="dataSet" @changed="fetchReplies"></paginator>

        <new-reply @created="addReply"></new-reply>
    </div>
</template>

<script>
import Paginator from './Paginator';
import Reply from './Reply';
import NewReply from './NewReply';
export default {
    components: {
        Paginator,
        NewReply,
        Reply
    },
    data() {
        return {
            replies: [],
            dataSet: {}
        }
    },
    created() {
        this.fetchReplies();
    },
    computed: {
        path() {
            return `${window.location.pathname}/replies`;
        }
    },
    methods: {
        fetchReplies(page) {
            axios.get(this.url(page)).then(this.refresh)
        },
        url(page) {
            if (!page) {
                let query = new URLSearchParams(window.location.search);

                page = query.has('page') ? parseInt(query.get('page')) : 1
            }
            return `${this.path}?page=${page}`;
        },
        refresh({ data: response }) {
            this.dataSet = response;
            this.replies = response.data;

            window.scrollTo(0, 0);
        },
        addReply(reply) {
            this.replies.push(reply);

            this.$emit('created');
        },
        filterReplies({ id }) {
            this.replies = this.replies.filter(i => i.id !== id);

            this.$emit('removed');
        }
    }
}
</script>

<style>

</style>
