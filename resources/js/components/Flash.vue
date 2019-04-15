<template>
    <div class="alert alert-success flash" v-if="message">
        <strong>Success!</strong> {{ message }}
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: String,
                default: ''
            }
        }
    }
</script>

<style lang="scss" scoped>
    .flash {
        position: fixed;
        bottom: 2rem;
        animation-name: flashMessage;
        animation-duration: 3s;
        visibility: hidden;
    }

    @keyframes flashMessage {
        0% {
            right: .5rem;
            opacity: 1;
            visibility: unset;
        }

        50% {
            right: 4rem;
            opacity: 1;
        }

        100% {
            right: .5rem;
            opacity: 0;
        }
    }
</style>
