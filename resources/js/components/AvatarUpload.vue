<template>
    <form :action="url" method="post" enctype="multipart/form-data" @change="onChange">
        <input type="file" name="avatar" accept="image/*" ref="fileInput" style="display: none">
        <img :src="avatar" alt="avatar" class="rounded-circle" :class="{'cursor-pointer': canUpdate}" width="80" height="80" @click="openFileInput">
    </form>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            required: true
        },
        avatar: {
            type: String,
            required: true
        },
        canUpdate: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        openFileInput() {
            if (!this.canUpdate) {
                return;
            }

            this.$refs.fileInput.click();
        },
        onChange(e) {
            if (! e.target.files.length) {
                return;
            }

            const avatar = e.target.files[0];

            const reader = new FileReader();

            reader.readAsDataURL(avatar);

            reader.onload = e => this.$emit('loaded', {
                src: e.target.result,
                avatar
            });
        }
    }
}
</script>

<style>

</style>
