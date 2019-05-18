<template>
    <div>
        <div class="d-flex align-items-center pb-3 border-bottom">
            <avatar-upload class="mr-2" :can-update="profile.can.update" :url="url" :avatar="selectedAvatar" @loaded="onLoad" />
            <h3> {{ profile.name }} </h3>
        </div>
    </div>
</template>

<script>
import AvatarUpload from './AvatarUpload'
export default {
    props: {
        profile: {
            type: Object,
            required: true
        }
    },
    components: {
        AvatarUpload
    },
    data: () => ({
        avatar: ''
    }),
    computed: {
        selectedAvatar() {
            return !!this.avatar.length ? this.avatar : this.profile.avatar_path
        },
        url() {
            return `/users/${this.profile.id}/avatar`;
        }
    },
    methods: {
        onLoad({ src, avatar }) {
            this.avatar = src;

            this.persist(avatar);
        },
        persist(avatar) {
            const form = new FormData();
            form.append('avatar', avatar);

            axios.post(`/users/${this.profile.name}/avatar`, form);
        }
    }
}
</script>

<style>

</style>
