<template>
    <div :class="'image-uploader container-'+this.id">
        <image-uploader
            :preview="true"
            accept="image/*"
            :maxHeight="1024"
            :quality="0.7"
            :id="this.id"
            :className="[this.id+' file-input', { 'fileinput--loaded ': hasImage }]"
            :capture="false"
            :debug="1"
            :autoRotate="true"
            outputFormat="verbose"
            @input="setImage"
        >
            <label :for="this.id" slot="upload-label">
                <div class="upload-area">
                    <div class="upload-caption">
                        <i class="fa fa-image"></i>
                        <br/>
                        {{ hasImage ? 'Replace' : 'Upload' }}
                    </div>
                </div>
            </label>
        </image-uploader>
    </div>
</template>
<script>
import config from './../../config.js';

export default {
    props: [
        'id'
    ],
    data(){
        return{
            hasImage : false,
            base64 : ''
        }
    },
    methods: {
        setImage: function(output) {
            this.hasImage = true;
            if(config.is_debug){
                console.log("image ready:");
                console.log(output);
            }

            if(typeof output.dataUrl !== 'undefined'){
                //image is ok, 
                //responsive image
                try{
                    let imgPreview = document.getElementsByClassName('container-'+this.id)[0].querySelector('.img-preview');
                    imgPreview.classList.add("img-fluid");
                    imgPreview.classList.add("img-thumbnail");
                }catch(err){
                    if(config.is_debug){
                        console.log("failed to make image preview responsive:");
                        console.log(err.message);
                    }
                }
                
                //show
                this.base64 = output.dataUrl;
                //sent emit to parent
                this.$emit('base64Result', output.dataUrl);
            }
        },
    }
}
</script>


<style>
.image-uploader label{
    display: block !important;
    width:150px;
    margin-top:20px;
}
.file-input {
  display: none;
}
.upload-area{
    background-color:#f2f2f2;
    border:2px dotted #bdbdbd;
    padding-top:10px;
    padding-bottom:10px;
}
.upload-caption{
    text-align: center;
    color :#696969;
}
</style>
