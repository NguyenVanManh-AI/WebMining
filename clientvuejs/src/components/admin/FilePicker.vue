<template>
  <div id="filepicker">
    <div class="container upload-card">
      <!--UPLOAD-->
      <form enctype="multipart/form-data" novalidate v-if="isInitial || isSaving">
        <h1>Upload Files</h1>
        <br><br>
        <div class="dropbox">
          <input type="file" multiple name="photos" :disabled="isSaving" 
            @change="filesChange($event.target.name, $event.target.files); 
            fileCount = $event.target.files.length" class="input-file">
            
            <p v-if="isInitial">
              Drag your file(s) here to begin<br> or click to browse <i class="fa-regular fa-image"></i>
            </p>
            <p v-if="isSaving">
              Uploading {{ fileCount }} files...
            </p>
        </div>
      </form>

      <!--SUCCESS-->
      <div v-if="isSuccess">
        <h2>Uploaded {{ uploadedFiles.length }} file(s) successfully.</h2>

         <!--File Previews-->
        <div class="preview-box">
          <div v-for="item in uploadedFiles" :key="item.id" class="img-container"> 
               <!--File Preview-->
            <img v-if="typecheck(item.fileName)" :src="item.url" class="preview-img" :alt="item.originalName">
            <img v-else src="../../assets/file.png" class="preview-img" :alt="item.originalName">
            <!--Remove btn to remove individual files-->
            <img  src="../../assets/error.png" v-on:click="removeFile(fileCount,uploadedFiles.indexOf(item));" class="close" alt="Remove">
            <!--File Name-->
            <p v-if="fname=ftruncate(item.fileName)" style="max-width: 100px;">{{fname}}</p>
          </div>
        </div>
        <!--  Submit to your storage service-->
        <!--For now just reset() back to the main menu-->
        <div style="display: flex;">
        <!-- <button class="btn-cancel" v-on:click="reset()">Cancel</button> -->
        <button type="button" class="btn btn-outline-danger" v-on:click="reset()">Cancel</button>
        <!-- <button class="btn" v-on:click="saveReal()">Submit</button> -->
        </div>

      </div>

      <!--FAILED-->
      <div v-if="isFailed">
        <h2>Uploaded failed.</h2>
        <p>
          <a href="javascript:void(0)" @click="reset()">Try again</a>
        </p>
        <pre>{{ uploadError }}</pre>
      </div>
    </div>
  </div>
</template>

<script>
  // swap as you need
   import { upload } from '../../uploadfile/file-upload.fake.service'; // fake service
   import BaseRequest from '../../restful/admin/core/BaseRequest';
   import useEventBus from '../../composables/useEventBus'

  const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

  export default {
    name: 'FilePicker',
    data() {
      return {
        uploadedFiles: [],
        fileCount: 0,
        uploadError: null,
        currentStatus: null,
        fname: '',
        images:[],
        num:0,
      }
    },
    computed: {
      //Upload Statuses
      isInitial() {
        return this.currentStatus === STATUS_INITIAL;
      },
      isSaving() {
        return this.currentStatus === STATUS_SAVING;
      },
      isSuccess() {
        return this.currentStatus === STATUS_SUCCESS;
      },
      isFailed() {
        return this.currentStatus === STATUS_FAILED;
      }
    },
    mounted() {
      this.reset();
      const { onEvent } = useEventBus()
      onEvent('eventUpfile',(idProduct)=>{
        this.saveReal(idProduct);
      })

      // upfile thành công thì reset lại hết 
      onEvent('eventResetUpfile',()=>{
        this.reset();
      })
    },
    methods: {
      reset() {
        // reset form to initial state
        this.currentStatus = STATUS_INITIAL;
        this.uploadedFiles = [];
        this.uploadError = null;
      },
      //This is where we implement the storage service
      save(formData) {
        this.currentStatus = STATUS_SAVING;
        //Implement your own storage service when upload() is called.
        //For test purposes, I added a delay service
        upload(formData)
         .then(x => {
            this.uploadedFiles = [].concat(x);
            this.currentStatus = STATUS_SUCCESS;
          })
          .catch(err => {
            this.uploadError = err.response;
            this.currentStatus = STATUS_FAILED;
          });
      },

      saveReal(idProduct){
        // console.log(this.images);
        for(var i=0;i<this.num;i++){
          var formData = new FormData;
          formData.set('photo',this.images[i]);
          BaseRequest.post('api/products/upfile?id='+idProduct,formData)
          .then(data => {
            // alert("thanh cong");
            // console.log("thanh cong");
            console.log(data);
          })
          .catch(error => {
            alert('false');
            console.log(error);
          })
        }

      },

      // saveReal(){
      //   console.log(this.images);
      //   var formData = new FormData;
      //   for(var i=0;i<this.num;i++){
      //     formData.append('photos',this.images[i]);
      //   }
      //   console.log(formData);
      //   BaseRequest.post('api/auth/upfile',formData)
      //     .then(data => {
      //       // alert("thanh cong");
      //       console.log("thanh cong");
      //       console.log(data);
      //     })
      //     .catch(error => {
      //       alert('false');
      //       console.log(error);
      //     })

      // },

      //Returns true for image data types for others false, for preview purpose
      typecheck(oInput) {
            var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
                if (oInput.length > 0) {
                    var blnValid = false;
                    for (var j = 0; j < _validFileExtensions.length; j++) {
                        var sCurExtension = _validFileExtensions[j];
                        if (oInput.substr(oInput.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                            blnValid = true;
                            break;
                        }
                    }
                    if (!blnValid) {
                        return false;
                    }
                }
         return true;
      },
      //Removes the selected file from the array.
      removeFile(fileCount,key) {
        fileCount=this.uploadedFiles.length;
        if(fileCount === 1)
        {
          this.uploadedFiles.splice(key, 1);
          this.images.splice(key, 1);
          this.num = this.images.length;
          this.reset();
        }
        else{
          this.uploadedFiles.splice(key, 1);
          this.images.splice(key, 1);
          this.num = this.images.length;
        }
      },
      //Func to truncate long filenames to short ones to display
      ftruncate(n) {
          var len = 7;
          var ext = n.substring(n.lastIndexOf(".") + 1, n.length).toLowerCase();
          var filename = n.replace('.'+ext,'');
          if(filename.length <= len) {
              return n;
          }
          filename = filename.substr(0, len) + (n.length > len ? '[...]' : '');
          return filename + '.' + ext;
      },
      // Handle file changes
      filesChange(fieldName, fileList) {

        this.num = fileList.length;
        for(var i=0;i<this.num;i++){
          this.images[i] = fileList[i];
        }
        console.log(this.images);

        const formData = new FormData();
        if (!fileList.length) return;
        // append the files to FormData
        Array.from(Array(fileList.length).keys())
          .map(x => {
            formData.append(fieldName, fileList[x], fileList[x].name);
          });

        // Save it to cloud
        console.log(formData);
        this.save(formData);
        console.log(formData);
      }
    },
  }

</script>

<style>
  .upload-card{
    border-radius: 20px;
    padding: 10px 30px;
    min-height: 300px;
    min-width: 300px;

    background-color: #ffffff;
    -webkit-box-shadow: 2px 4px 20px 2px #dadada;
    box-shadow: 2px 4px 20px 2px #dadada;
    margin: auto;
    width: 50%;
  }
  .dropbox {
    margin: auto;
    width: 70%;
    background: #f8f8f8;
    border-radius: 20px;
    color: dimgray;
    padding: 10px 10px;
    min-height: 200px;
    position: relative;
    cursor: pointer;
  }
  
  .input-file {
    opacity: 0;/* invisible but it's there! */
    left: 0px; 
    width: 100%;
    height: 200px;
    position: absolute;
    cursor: pointer;
  }
  
  .dropbox:hover {
    background: #E8F5E9;
  }
  
  .dropbox p {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 0;
  }
  .img-container{
    position: relative;
    display: inline-block;
    padding: 10px;
  }
  .preview-img{
    width: 80px;
    padding: 10px;
    border: 1px dotted #b3b3b39e;
  }
  .preview-box{
    display: inline-block;
  }
  .close{
    position: absolute;
    top: 0px;
    right: 0px;
    width: 20px;
  }
  .cancel{
    color: #545454;
    text-decoration: none;
  }
  .btn {
    transition: all 0.5 ease;
  }
/* .btn{
    margin: 0 auto;
    display: flex;
    justify-content: center;
    background-color: #fff;
    border: 0px;
    box-shadow: 2px 3px 12px 1px #ddd;
    border-radius: 3px;
    width: 80px;
    height: 30px;
    display:none;
  } */
    /* .btn-cancel{
    margin: 0 auto;
    display: flex;
    justify-content: center;
    background-color: #f5736a;
    border: 0px;
    box-shadow: 2px 3px 12px 1px #ddd;
    border-radius: 3px;
    width: 80px;
    height: 30px;
    display: flex;
    align-content: center;
  } */
</style>