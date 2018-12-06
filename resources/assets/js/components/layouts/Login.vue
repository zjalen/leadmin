<template>
    <el-row class="full-width full-height flex-center">
        <el-col v-loading="submitting" class="login-box" :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
            <div class="box-title"><h1>后台登录</h1></div>
            <el-row style="margin-bottom: 20px;">
                <el-col :span="24">
                    <el-input v-model="form.username" placeholder="请输入帐号">
                        <template slot="prepend">帐号</template>
                    </el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span="24">
                    <el-input v-model="form.password" type="password" placeholder="请输入密码">
                        <template slot="prepend">密码</template>
                    </el-input>
                </el-col>
            </el-row>
            <el-row style="margin-bottom: 20px;">
                <el-col :span="12">
                    <div style="height: 100%;margin-right: 10px;">
                        <a href="javascript:" @click="refresh()"><img :src="captcha_src" v-if="show_captcha"/></a>
                    </div>
                </el-col>
                <el-col :span="12">
                    <el-input v-model="form.captcha" @keyup.enter.native="submit" placeholder="输入图片验证码">
                    </el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="24">
                    <el-button style="width:100%" @click="submit" type="primary">登录</el-button>
                </el-col>
            </el-row>
            <el-row style="margin-top: 10px;">
                <el-col :span="12">
                    <el-checkbox v-model="form.remember">记住登录状态(公用电脑勿使用)</el-checkbox>
                </el-col>
            </el-row>
        </el-col>
    </el-row>
</template>
<script>
    export default {
        props: [ 'src' ],
        components: {
        },
        data() {
            return {
                form: {
                    password: '',
                    username: '',
                    captcha: '',
                    remember: false,
                },
                host:'',
                captcha_src: '',
                show_captcha: true,
                submitting: false,
            };
        },
        mounted() {
            // let url = 'http://localhost:8000/api/captcha';
            // this.captcha_url = img;
            // console.log(this.captcha_url);
            this.captcha_src = this.src.url;
            this.host =  window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port : '');
        },
        methods: {
            submit() {
                let that = this;
                for(let key in this.form){
                    if (this.form[key] === ''){
                        that.$alert('输入不能为空');
                        return;
                    }
                }
                let url = this.host + '/admin/auth/login';
                that.submitting = true;
                axios.post(url, this.form).then(function(response){
                    console.log(response.data);
                    if(response.data.error_code){
                        that.refresh();
                        that.submitting = false;
                        return that.$alert(response.data.error_message);
                    }
                    window.location.href = response.data.url;
                    that.submitting = false;
                }).catch(function (res) {
                    console.log(res);
                    that.refresh();
                    that.submitting = false;
                })
            },
            refresh() {
                this.captcha_src += Math.random();
            },
        }
    }
</script>
<style scoped>
</style>