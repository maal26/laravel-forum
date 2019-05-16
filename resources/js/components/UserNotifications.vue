<template>
    <li class="nav-item dropdown" v-if="hasNotifications">
        <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a
                :href="notification.data.link"
                class="dropdown-item cursor-pointer"
                v-for="notification in notifications"
                :key="notification.id"
                @click="markAsRead(notification)"
            >
                {{ notification.data.message }}
            </a>
        </div>
    </li>
</template>

<script>
export default {
    props: {
        userName: {
            type: String,
            required: true
        }
    },
    data: () => ({
        notifications: []
    }),
    mounted() {
        this.fetchUnreadNotifications();
    },
    computed: {
        hasNotifications() {
            return !!this.notifications.length;
        }
    },
    methods: {
        fetchUnreadNotifications() {
            axios.get(`/profiles/${this.userName}/notifications`)
                .then(({ data }) => this.notifications = data);
        },
        markAsRead(notification) {
            axios.delete(`/profiles/${this.userName}/notifications/${notification.id}`)
                .then(_ => {
                    this.notifications = this.notifications.filter(n => n.id !== notification.id);
                })
        }
    }
}
</script>

<style>

</style>
