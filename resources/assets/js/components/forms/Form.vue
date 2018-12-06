<template>
    <div class="form-content">
        <el-form ref="form" :model="form1.body" label-width="80px">
            <el-form-item v-for="(value, key) in form1.headers" :key="key" :label="value.title">
                <el-select clearable v-if="value.select" v-model="form1.body[value.name]" :placeholder="value.select.name">
                    <el-option v-for="(v, k) in value.select" :key="k" :label="v.label" :value="v.value"></el-option>
                </el-select>
                <el-switch v-else-if="value.switch" v-model="form1.body[value.name]"></el-switch>
                <el-col :span="12" v-else-if="value.date">
                    <el-date-picker value-format="yyyy-mm-dd" type="date" placeholder="选择日期" v-model="form1.body[value.name]" style="width: 100%;"></el-date-picker>
                </el-col>
                <el-col :span="12" v-else-if="value.datetime">
                    <el-date-picker value-format="yyyy-mm-dd H:mm:ss" type="datetime" placeholder="选择时间" v-model="form1.body[value.name]" style="width: 100%;"></el-date-picker>
                </el-col>
                <el-checkbox-group v-else-if="value.multiselect" v-model="form1.body[value.name]">
                    <el-checkbox v-for="(v, k) in value.multiselect" :key="k" :label="v.id">{{v.name}}</el-checkbox>
                </el-checkbox-group>
                <el-radio-group v-else-if="value.radio" v-model="form1.body[value.name]">
                    <el-radio  v-for="(v, k) in value.radio" :key="k" :label="v.id">{{v.name}}</el-radio>
                </el-radio-group>
                <span v-else-if="value.upload">
                    <el-upload
                            class="avatar-uploader"
                            :headers="headers"
                            :data="{name: value.name}"
                            :show-file-list="false"
                            name="image"
                            :action="value.upload"
                            :on-success="onUpload">
                        <img v-if="form1.body[value.name]" :src="form1.body[value.name]" class="avatar">
                        <i v-else class="fa fa-plus avatar-uploader-icon"></i>
                    </el-upload>
                    <el-button v-if="form1.body[value.name]" size="small" @click="onRemove(value.name)" type="danger">移除</el-button>
                </span>
                <el-input v-show="!value.hide" :disabled="value.disabled" :readonly="value.readonly" @keyup.enter="onSubmit" v-else-if="value.textarea" type="textarea" v-model="form1.body[value.name]"></el-input>
                <el-input v-show="!value.hide" :disabled="value.disabled" :readonly="value.readonly" @keyup.enter="onSubmit" v-else v-model="form1.body[value.name]"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button @click="onCancel"><i style="margin-right: 5px;" class="fa fa-chevron-left"></i>取消</el-button>
                <el-button type="primary" @click="onSubmit"><i style="margin-right: 5px;" class="fa fa-check"></i>提交</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script>
    export default {
        props: ['form_data'],
        components: {
        },
        data() {
            return {
                form1: {},
                headers: {'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                images:[],
            }
        },
        computed: {

        },
        created() {
        },
        mounted() {
            this.form1 = this.form_data;
            console.log(this.form1);
        },
        methods: {
            onSubmit() {
                this.$emit('action',{act: 'submit', data: this.form1.body});
            },
            onCancel() {
                this.$emit('action',{act: 'cancel'});
            },
            onUpload(response, file) {
                this.images[response.name] = response.url;
                this.form1.body[response.name] = response.path;
            },
            onRemove(obj) {
                console.log(obj);
                this.form1.body[obj] = null;
            }
        }
    }
</script>
<style>
    .form-content {
        width: 100%;
        margin: 0 10px;
    }

    .avatar-uploader .el-upload {
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
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
    }
    .avatar {
        width: 100px;
        height: 100px;
        display: block;
    }

</style>