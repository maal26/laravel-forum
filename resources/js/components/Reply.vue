<template>
    <div class="card mb-3" :id="`reply-${reply.id}`">
        <div class="card-header d-flex align-items-center justify-content-between" :class="{'bg-success': isBest}">
            <div>
                <a :href="`/profiles/${reply.owner.name}`">
                    {{ reply.owner.name }}
                </a>
                said {{ reply.created_at | ago }}...
            </div>
            <div v-if="signedIn">
                <favorite :reply="reply"></favorite>
            </div>
        </div>
        <div v-if="editing">
            <form @submit.prevent="update">
                <textarea name="" class="form-control mb-2" v-model="body" required></textarea>

                <div class="form-group">
                    <button class="btn btn-sm btn-primary ml-2">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                </div>
            </form>
        </div>
        <div class="card-body" v-else v-html="body">
        </div>
        <div class="card-footer d-flex">
            <div v-if="reply.can.update">
                <button class="btn btn-sm btn-info mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-sm btn-light ml-auto" @click="markBestReply" v-if="!isBest">Best Reply?</button>
        </div>
    </div>
</template>

<script>
import Favorite from './Favorite';
import moment from 'moment';

export default {
    props: {
        reply: {
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
            body: this.reply.body,
            isBest: false
        }
    },
    computed: {
        signedIn() {
            console.log(window.App)
            return window.App.signedIn;
        }
    },
    filters: {
        ago(value) {
            return moment(value).fromNow();
        }
    },
    methods: {
        update() {
            axios.patch(`/replies/${this.reply.id}`, {
                body: this.body
            });

            this.editing = false;
        },
        destroy() {
            axios.delete(`/replies/${this.reply.id}`)
                .then(_ => this.$emit('deleted', this.reply));
        },
        markBestReply() {
            axios.post(`/replies/${this.reply.id}/best`)
                .then(_ => this.isBest = true);
        }
    }
}
</script>

<style lang="scss" scoped>
.bg-success {
    background-color: #DFF0D8 !important;
}
</style>
