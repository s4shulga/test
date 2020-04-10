<template>
    <div class="row" v-if="isReady">
        <router-link :to="{name: 'createProfile'}" class="btn btn-success btn-xs btn-default float-right">
            Add new profile
        </router-link>
        <b-table
                head-variant="light"
                bordered
                hover
                :items="preparedData"
                :fields="fields">
            <template v-slot:cell(image)="data">
                <img  :src="data.item.image" @error="replaceByDefault" width="100" height="100">
            </template>
            <template v-slot:cell(edit)="data">
                <router-link :to="{name: 'editProfile', params:{ id: data.item.id }}" class="btn btn-success btn-xs btn-default">
                    Edit
                </router-link>
            </template>
            <template v-slot:cell(delete)="data">
                <a href="#"
                   class="btn btn-xs btn-danger"
                   v-on:click="deleteEntry(data.item.id)">
                    Delete
                </a>
            </template>
        </b-table>
    </div>

</template>
<script>
    import { mapState } from 'vuex';
    import { mapGetters } from 'vuex';

    export default {
        name: 'profiles',
        data() {
            return {
                fields: ['id', 'name', 'image', 'edit', 'delete'],
                profileDialogVisible: true,
                currentProfile: '',
                defaultImage: require('../../../public/uploads/default.png'),
                preparedData: [],
                data: [],
            };
        },
        computed: {
            ...mapState(['profiles', 'isReady']),
            ...mapGetters(['getItems']),
            getItems() {
                return this.$store.state.profiles;
            },

            onFileChange(e) {
                const file = e.target.files[0];
                let image_link = URL.createObjectURL(file);
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    EventBus.$emit( 'product-image-upload', this.color_name, this.type, image_link, this.index, reader.result );
                };
            },
            profiles: {
                get: function() {
                    return  this.prepareData(this.$store.state.profiles);
                },
                set: function( val ) {
                    this.profiles = val;
                }
            }
        },
        watch: {
          profiles:function(newVal, oldVal) {
              this.profiles = newVal;
          }
        },
        beforeCreate() {
            this.$store.dispatch('getAllProfiles');
        },
        methods: {
            replaceByDefault(e) {
                e.target.src = this.defaultImage
            },
            prepareData(profiles) {
                let items =[];
                profiles.forEach((profile, key) => {
                    let currentProfile = {
                        'id': profile.id,
                        'name': profile.name,
                        'image': profile.profile_images[0] ? profile.profile_images[0].profile_image_path : this.defaultImage,
                        'edit' : 'edit',
                        'delete': 'delete',
                    }
                    items.push(currentProfile);
                });
                this.preparedData = items;
            },
            viewProfile(profileIndex) {
                const profile = this.profiles[profileIndex];
                this.currentProfile = profile;
                this.profileDialogVisible = true;
            },
            deleteEntry(id) {
                this.$store.dispatch('deleteProfileById', id);
            },
        }
    }
</script>
