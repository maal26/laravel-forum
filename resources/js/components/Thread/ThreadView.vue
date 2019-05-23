<script>
import Replies from '../Replies.vue';
import SubscribeButton from '../SubscribeButton.vue';
export default {
    props: {
        thread: {
            type: Object,
            required: true
        }
    },
    components: {
        Replies,
        SubscribeButton
    },
    data() {
        return {
            count: this.thread.replies_count,
            locked: this.thread.locked,
        }
    },
    computed: {
        btnLabel() {
            return this.locked ? 'Unlock' : 'Lock';
        }
    },
    methods: {
        toggleLock() {
            const method = this.locked ? 'delete' : 'post';

            axios[method](`/locked-threads/${this.thread.slug}`)
                .then(_ => this.locked = !this.locked);
        }
    }
}
</script>
