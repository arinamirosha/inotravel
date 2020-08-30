<template>
    <div>
        <label for="image" class="col-form-label btn btn-outline-dark btn-block mt-3">Загрузить фото</label>

        <input type="file" class="form-control-file" style="display: none" @change="uploadImageFromForm" id="image" name="image">
    </div>
</template>

<script>
export default {
    mounted() {
        console.log('Component mounted.')
    },

    methods: {
        uploadImageFromForm() {
            $('#myimage').hide();
            let imgFakePath = $('#image').val();
            let imgName = imgFakePath.substring(imgFakePath.lastIndexOf('\\')+1,imgFakePath.length);
            $('#mydiv').html(imgName);
            $('#deleteImage').prop('checked', false);
        }
    },
}
</script>
