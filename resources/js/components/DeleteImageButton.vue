<template>
    <div>
        <div class="btn btn-outline-secondary btn-block" @click="deleteImageFromForm">Удалить фото</div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        methods: {
            deleteImageFromForm() {
                $('#image').val('');
                $('#mydiv').html('Нет фото');
                $('#myimage').hide();
                $('#deleteImage').prop('checked', true);
            }
        },
    }
</script>
