<template>
    <button class="btn btn-sm float-right" :class="isSubscribed" @click="subscribe">
        {{ btnLabel }}
    </button>
</template>

<script>
export default {
    props: {
        active: {
            type: Boolean,
            required: true
        }
    },
    data() {
        return {
            subscribed: this.active
        }
    },
    computed: {
        isSubscribed() {
            return this.subscribed ? 'btn-danger' : 'btn-primary';
        },
        btnLabel() {
            return this.subscribed ? 'Unsubscribe' : 'Subscribe';
        }
    },
    methods: {
        subscribe() {
            const URI = `${window.location.pathname}/subscriptions`;
            const requestType = this.subscribed ? 'delete' : 'post';

            axios[requestType](URI).then(_ => this.subscribed = !this.subscribed);
        }
    },
    watch: {
        message() {
            setTimeout(_ => this.message = '', 2500)
        }
    }
}
</script>

<style>

</style>
