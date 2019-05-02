<script>
import Replies from '../Replies.vue';
export default {
    props: {
        repliesCount: {
            type: Number,
            required: true
        }
    },
    components: {
        Replies
    },
    data() {
        return {
            count: this.repliesCount
        }
    }
}
</script>
