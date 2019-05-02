<template>
    <div class="card mb-3" :id="`reply-${reply.id}`">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <a :href="`/profiles/${reply.owner.name}`">
                    {{ reply.owner.name }}
                </a>
                said {{ reply.created_at }}...
            </div>
            <!-- @auth -->
            <div v-if="signedIn">
                <favorite :reply="reply"></favorite>
            </div>
            <!-- @endauth -->
        </div>
        <div v-if="editing">
            <textarea name="" class="form-control mb-2" v-model="body"></textarea>

            <div class="form-group">
                <button class="btn btn-sm btn-primary ml-2" @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
        </div>
        <div class="card-body" v-else v-text="body">
        </div>
        <div class="card-footer d-flex" v-if="reply.can.update">
            <button class="btn btn-sm btn-info mr-2" @click="editing = true">Edit</button>
            <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
import Favorite from './Favorite';

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
            body: this.reply.body
        }
    },
    computed: {
        signedIn() {
            return window.App.signedIn;
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
        }
    }
}
</script>
