<template>
    <div>
        <div v-if="isSignedIn">
            <textarea
                name="body"
                rows="5"
                class="form-control"
                :class="{'is-invalid': errorMessage}"
                placeholder="Have something to say?"
                v-model="body">
            </textarea>
            <span class="small invalid-feedback">{{ errorMessage }}</span>
            <button class="btn btn-primary mt-3" @click="addReply">Post</button>
        </div>

        <div v-if="!isSignedIn">
            <p class="text-center">
                Please <a :href="loginURL">sign in </a> to participate in this discussion
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        body: '',
        errorMessage: ''
    }),
    computed: {
        isSignedIn() {
            return window.App.signedIn;
        },
        loginURL() {
            return `${window.location.origin}/login`;
        },
        endpoint() {
            return `${window.location.pathname}/replies`;
        },
    },
    methods: {
        addReply() {
            axios.post(this.endpoint, { body: this.body })
                .then(({ data }) => {
                    this.body = '';
                    this.errorMessage = '';
                    this.$emit('created', data);
                })
                .catch(e => {
                    this.errorMessage = e.response.data.message;
                })
        }
    }
}
</script>

<style>

</style>
