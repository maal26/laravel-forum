<script>
    import Favorite from './Favorite';

    export default {
        props: {
            attributes: {
                type: Object,
                required: true
            }
        },
        components: {
            Favorite
        },
        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        },
        methods: {
            update() {
                axios.patch(`/replies/${this.attributes.id}`, {
                    body: this.body
                });

                this.editing = false;
            },
            destroy() {
                axios.delete(`/replies/${this.attributes.id}`)
                    .then(_ => this.$el.style = 'display: none');
            }
        }
    }
</script>
