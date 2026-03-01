<template>
    <button 
        :class="b_class" 
        data-bs-toggle="modal"
        data-bs-whatever="@getbootstrap"
        :data-bs-target="data_bs_target"
        :type="b_type"
        @click.prevent="handleClick($event)"
    >
        <slot></slot>
    </button>
    </template>
<script>
export default {

    props: {
        id_element: {
            type: String
        },
        b_class: {
            type: String,
            default: 'btn btn-primary'
        },
        data_bs_target: {
            type: String,
            default: "#add-event"
        },
        b_type: {
            type: String,
            default: 'button'
        }
    },
    methods: {
        handleClick(event) {
            this.$emit('button-click', this.id_element, event);
        }
    }
} 
</script>