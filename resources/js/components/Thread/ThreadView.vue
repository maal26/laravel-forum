<script>
import Replies from '../Replies.vue';
import SubscribeButton from '../SubscribeButton.vue';
export default {
    props: {
        repliesCount: {
            type: Number,
            required: true
        }
    },
    components: {
        Replies,
        SubscribeButton
    },
    data() {
        return {
            count: this.repliesCount
        }
    }
}
</script>
