<template>
    <div class="card mt-4">
        <router-link to="/" class="btn btn-default" >Back</router-link>
        <div class="card-header">New Profile</div>
        <div class="card-body">
            <div v-if="statusMessage" :class="{'alert-success': status, 'alert-danger': !status}" class="alert" role="alert">
                {{statusMessage}}
            </div>
            <form>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Name</label>
                    <input v-model="name" type="text" class="form-control" id="name" placeholder="Profile Name" required>
                </div>
                <div class="">
                    <el-upload action="/" list-type="picture-card"
                               multiple
                               accept="image/jpeg,image/png,image/jpg"
                               :beforeUpload="onBeforeUpload"
                               :on-preview="handlePictureCardPreview"
                               :on-change="updateImageList"
                               :on-remove="removeImage"
                               :auto-upload="false">
                        <i class="el-icon-plus"></i>
                    </el-upload>
                    <el-dialog :visible.sync="dialogVisible">
                        <img width="100%" :src="dialogImageUrl" alt="">
                    </el-dialog>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="button" @click="createProfile" class="btn btn-success">
                {{isCreatingProfile ? 'Creating..' : 'Create Profile'}}
            </button>
        </div>
    </div>
</template>
<script>
    import { mapActions } from 'vuex';

    export default {
        name: 'create-profile',
        props: ['profiles'],
        data() {
            return {
                dialogImageUrl: '',
                dialogVisible: false,
                imageList: [],
                statusMessage: '',
                status: true,
                isCreatingProfile: false,
                name: '',
            };
        },
        computed: {
           ...mapActions['getAllProfiles']
        },
        methods: {
            onBeforeUpload(file)
            {
                const isIMAGE = file.type === 'image/jpeg'||'image/gif'||'image/png';
                const isLt1M = file.size / 1024 / 1024 < 1;

                if (!isIMAGE) {
                    this.$message.error('Upload file can only be in picture format!');
                }
                if (!isLt1M) {
                    this.$message.error('Upload file size cannot exceed 1 MB!');
                }
                return isIMAGE && isLt1M;
            },
            updateImageList(file) {
                this.imageList.push(file.raw);
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            showNotification(message) {
                this.statusMessage = message;
                setTimeout(() => {
                    this.statusMessage = '';
                }, 3000)
            },
            removeImage(file) {
                var index = this.imageList.map(function(x) {return x.uid; }).indexOf(file.uid);
                if (index > -1) {
                    this.imageList.splice(index, 1);
                }
            },
            validateForm() {
                if(!this.name) {
                    this.status = false;
                    this.showNotification('Profile name cannot be empty');
                    return false;
                }
                if(this.imageList.length < 1) {
                    this.status = false;
                    this.showNotification('You need to select an image');
                    return false;
                }
                if(this.imageList.size > 3072 * 1024) {
                    this.status = false;
                    this.showNotification('File size greater than 3 mb');
                    return false;
                }
                this.status = true;
                this.showNotification('Profile was created!');
                return true;
            },
            createProfile() {
                event.preventDefault();
                if( !this.validateForm()) {
                    return false;
                }

                this.isCreatingProfile = true;
                let formData = new FormData();

                formData.append('name', this.name);
                $.each(this.imageList, function (key, image) {
                    formData.append(`images[${key}]`, image);
                });

                this.$store.dispatch('createProfile', { data: formData }, )
                    .then((res) => {
                        this.name = '';
                        this.imageList = [];
                        this.status = true;
                        this.showNotification('Profile Successfully Created');
                        this.isCreatingProfile = false;
                    })
            }
        }
    }
</script>
<style>
    .avatar-uploader el.upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>
