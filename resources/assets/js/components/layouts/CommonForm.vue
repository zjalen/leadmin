<template>
    <div class="form-content">
        <div class="content-header">
            {{form_data.title}}
            <span class="desc">{{form_data.description}}</span>
        </div>
        <div class="box">
            <el-row class="box-form">
                <el-col :span="16">
                    <custom_form v-loading="loading" :form_data="form_data" @action="onAct"></custom_form>
                </el-col>
            </el-row>
        </div>
    </div>
</template>
<script>
    import custom_form from '../forms/Form';
    export default {
        props: ['form_data'],
        components: {
            custom_form,
        },
        data() {
            return {
                is_create: true,
                loading: true,
            }
        },
        computed: {

        },
        created() {
        },
        mounted() {
            this.loading = false;
            if (this.form_data.body.id){
                this.is_create = false;
            }
        },
        methods: {
            onAct(obj) {
                switch (obj.act) {
                    case 'cancel':
                        window.history.back(-1);
                        break;
                    case 'submit':
                        if (this.is_create){
                            this.onCreate();
                        } else {
                            this.onUpdate();
                        }
                        break;
                }
            },
            onCreate() {
                let url = window.location.href.split('/create')[0];
                let that = this;
                this.loading = true;
                this.$axios.post(url, this.form_data.body).then(function(response){
                    if (!response.data.error_code){
                        that.$message({
                            message: '提交成功',
                            type: 'success'
                        });
                        window.location.href = window.location.href.split('/create')[0];
                    }else{
                        that.$message({
                            message: response.data.error_message,
                            type: 'error'
                        });
                    }
                    that.loading = false;
                }).catch(function(response) {
                    if (response.errors) {
                        that.$message({
                            message: JSON.stringify(response.errors),
                            type: 'error'
                        });
                    }else {
                    }
                    that.loading = false;
                });
            },
            onUpdate() {
                let url = window.location.href.split('/edit')[0];
                let that = this;
                this.loading = true;
                this.$axios.put(url, this.form_data.body).then(function(response){
                    if (!response.data.error_code){
                        that.$message({
                            message: '提交成功',
                            type: 'success'
                        });
                        window.location.href = window.location.href.split( '/' + that.form_data.body.id + '/edit')[0];
                    } else{
                        that.$message({
                            message: response.data.error_message,
                            type: 'error'
                        });
                    }
                    that.loading = false;
                }).catch(function(error) {
                    if (error.response.status === 422) {
                        that.$message({
                            message: JSON.stringify(error.response.data.errors),
                            type: 'error'
                        });
                    } else {
                    }
                    that.loading = false;
                });
            }
        }
    }
</script>
<style scoped>
    .form-content {
        width: auto;
        padding: 10px 15px;
    }

    .content-header {
        font-size: 1.6rem;
        font-weight: 400;
        margin-bottom: 10px;
    }

    .desc {
        font-size: 70%;
        font-weight: 300;
    }

    .box {
        width: auto;
    }

    .box-form {
        margin: 20px 0 0 0;
    }

</style>