<template>
    <div class="table-content">
        <el-form ref="form" :model="form1.body" label-width="80px">
            <el-form-item v-for="(value, key) in form1.headers" :key="key" :label="value.title">
                <el-select v-if="value.select" v-model="form1.body[value.name]" :placeholder="value.select.name">
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
                <el-input @keyup.enter="onSubmit" v-else-if="value.textarea" type="textarea" v-model="form1.body[value.name]"></el-input>
                <el-input @keyup.enter="onSubmit" v-else v-model="form1.body[value.name]"></el-input>
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
            }
        },
        computed: {

        },
        created() {
        },
        mounted() {
            this.form1 = this.form_data;
        },
        methods: {
            onSubmit() {
                this.$emit('action',{act: 'submit', data: this.form1.body});
            },
            onCancel() {
                this.$emit('action',{act: 'cancel'});
            },
        }
    }
</script>
<style scoped lang="less">
    .pages {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }
    .header {
        font-family: 'Source Sans Pro',sans-serif;
        font-size: 1.6rem;
        font-weight: 400;
        margin-left: 8px;
        margin-bottom: 10px;
    }
    .table-content {
        width: 100%;
        margin: 0 10px;
    }
    small {
        font-size: 60%;
        font-weight: 300;
    }

    .markdown-area {
        line-height: 23px;
    }

</style>