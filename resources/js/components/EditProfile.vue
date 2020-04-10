<template>
    <div class="card mt-4" v-if="profile">
        <router-link to="/" class="btn btn-success btn-default" @click="updateProfileList">Back</router-link>
        <div class="card-header">Edit Profile</div>
        <div class="card-body">
            <div v-if="statusMessage" :class="{'alert-success': status, 'alert-danger': !status}" class="alert" role="alert">
                {{statusMessage}}
            </div>
            <form>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Name</label>
                    <input v-model="profile.name" type="text" class="form-control" id="name" placeholder="Profile Name" required>
                </div>
                <div class="">
                    <el-upload action="/"
                               list-type="picture-card"
                               multiple
                               accept="image/jpeg,image/png,image/jpg"
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
            <button type="button" @click="updateProfile" class="btn btn-success">
                {{isUpdatingProfile ? 'Updating..' : 'Update Profile'}}
            </button>
        </div>
        <div class="field">
            <div class="title">Already existing images</div>
            <div v-for="(image, index) in this.profile.profile_images"  :key="index" class="photo-wrapper" >
                    <img :src="image.profile_image_path" @error="replaceByDefault" width="100" height="100" alt="profile_image">
                    <div>
                        <b-icon-x-circle @click="deleteExistingImage(image.id, index)"></b-icon-x-circle>
                    </div>
            </div>

        </div>
    </div>
</template>
<script>
    import { mapState } from 'vuex';
    export default {
        name: 'update-profile',
        props: ['id'],
        data() {
            return {
                defaultImage: require('../../../public/uploads/default.png'),
                originalUrl: window.location.origin,
                dialogImageUrl: '',
                dialogVisible: false,
                imageList: [],
                statusMessage: '',
                status: true,
                isUpdatingProfile: false,
                file: null,
            };
        },
        computed: {
            ...mapState(['profile']),
        },
        getProfile() {
            return this.$store.state.profile;
        },
        profile: {
            get: function() {
                return  this.$store.state.profile;
            },
            set: function( val ) {
                this.profile = val;
            }
        },
        created(){
            this.$store.dispatch('getAllProfiles');
            this.$store.dispatch('getProfileById', this.id);

        },
        methods: {
            replaceByDefault(e) {
                e.target.src = this.defaultImage
            },
            removeImage(file) {
                var index = this.imageList.map(function(x) {return x.uid; }).indexOf(file.uid);
                console.log(index);
                if (index > -1) {
                    this.imageList.splice(index, 1);
                }
            },
            deleteExistingImage(id, index) {
                this.$store.dispatch('deleteExistingImage', id)
            },
            updateProfileList() {
                this.$store.dispatch('getAllProfiles');
            },
            updateImageList(file) {
                this.imageList.push(file.raw);
            },
            handlePictureCardPreview(file) {
                console.log(file.url);
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            showNotification(message) {
                this.statusMessage = message;
                setTimeout(() => {
                    this.statusMessage = '';
                }, 3000)
            },
            validateForm() {
                if(!this.profile.name) {
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
                this.showNotification('Profile was updated!');
                return true;
            },
            updateProfile() {
                event.preventDefault();
                if( !this.validateForm()) {
                    return false;
                }

                this.isUpdatingProfile = true;
                let formData = new FormData();

                formData.append('name', this.profile.name);
                $.each(this.imageList, function (key, image) {
                    formData.append(`images[${key}]`, image);
                });

                this.$store.dispatch('updateProfile', { profileId: this.id, data: formData }, )
                    .then((res) => {
                        this.status = true;
                        this.imageList = [];
                        this.showNotification('Profile Successfully Updated!');
                        this.isUpdatingProfile = false;
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

    .photo-wrapper {
        float: left;
        margin: 5px;
        position: relative;
    }
    .title {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.125);
    }
    .photo-wrapper > div {
        position: absolute;
        top: 5px;
        right: 5px;
        cursor: pointer;
    }
</style>
