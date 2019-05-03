<template>
    <nav aria-label="..." v-if="shouldPaginate">
        <ul class="pagination">
            <li class="page-item" :class="{'disabled': !prevUrl}">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true" @click.prevent="prevPage">Previous</a>
            </li>
            <li class="page-item" :class="{'disabled': !nextUrl}">
                <a class="page-link" href="#" @click.prevent="nextPage">Next</a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    props: {
        dataSet: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            page: 1,
            prevUrl: false,
            nextUrl: false
        }
    },
    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.nextUrl = this.dataSet.next_page_url;
            this.prevUrl = this.dataSet.prev_page_url;
        },
        page() {
            this.broadcast().updateUrl();
        }
    },
    computed: {
        shouldPaginate() {
            return !!this.prevUrl || !!this.nextUrl;
        }
    },
    methods: {
        prevPage() {
            if (this.prevUrl) {
                this.page--;
            }
        },
        nextPage() {
            if (this.nextUrl) {
                this.page++;
            }
        },
        broadcast() {
            return this.$emit('changed', this.page);
        },
        updateUrl() {
            window.history.pushState(null, null, `?page=${this.page}`);
        }
    }
}
</script>

<style>

</style>
