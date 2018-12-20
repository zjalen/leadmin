<template>
    <div class="table-container">
        <el-table :data="table_data.body" fit stripe @sort-change="onSort">
            <el-table-column :type="value.type" :label="value.title" :prop="value.name" :sortable=value.sortable v-for ="(value,key) in table_data.headers" :width="value.width + 'px'"  :key="key" >

                <template slot-scope="scope">
                    <span v-if="value.switch">
                        <el-switch
                                v-model="scope.row[value.name]"
                                @change="onSwitch(scope.row)"
                                active-color="#13ce66"
                                inactive-color="#999999">
                        </el-switch>
                    </span>
                    <span v-else-if="value.image">
                        <div class="image-box">
                            <img class="image" :src="scope.row[value.name]">
                        </div>
                    </span>

                    <span v-else-if="value.avatar">
                        <div v-if="scope.row[value.name]" class="image-box">
                            <img class="image avatar" :src="scope.row[value.name]">
                        </div>
                        <span v-else class="label label-danger">
                            暂无用户
                        </span>
                    </span>

                    <span v-else-if="value.action">
                        <a href="javascript:" @click="onAction(value.action, scope.row[value.name])">
                            <span v-if="value.text_tag" class="label" :class="value.text_tag">{{scope.row[value.name]}}</span>
                            <span v-else>{{scope.row[value.name]}}</span>
                        </a>
                    </span>
                    <span v-else-if="value.text_tag">
                        <span class="label" :class="value.text_tag">{{scope.row[value.name]}}</span>
                    </span>
                    <span v-else-if="value.is_text_tag">
                        <span class="label label-success" v-if="scope.row[value.name] == 0">正常</span>
                        <span class="label label-danger" v-else-if="scope.row[value.name] == 1">异常</span>
                        <span class="label label-danger" v-else></span>
                    </span>
                    <span v-else-if="value.multiselect">
                        <span v-for="(v,k) in scope.row[value.name]" :key="k" class="label label-primary" style="margin-left: 2px;">{{v.name ? v.name : v}}</span>
                    </span>
                    <span v-else-if="value.china_area">
                        {{CodeToText[scope.row[value.name]]}}
                    </span>

                    <span v-else-if="value.link">
                        <a :href="value.link">{{scope.row[value.name]}}</a>
                    </span>

                    <span v-else-if="value.expand">
                        <ul style="list-style:none;margin:0;padding:0">
                            <li v-for ="(value,key) in scope.row[value.name]" :key="key" style="float: left;padding: 0px 30px;">
                                <span style="width: 90px;color: #99a9bf;">{{key}}:</span>
                                <span style="margin-right: 0;margin-bottom: 0;width: 50%;">{{value}}</span>
                            </li>
                        </ul>
                    </span>




                    <span v-else>{{scope.row[value.name]}}</span>


                </template>
            </el-table-column>

            <el-table-column
                    v-if="table_data.actions"
                    :width="table_data.actions.width"
                    label="操作">
                <template slot-scope="scope">
                    <span v-if="table_data.actions.button">
                         <el-button v-for="(value,key) in table_data.actions.button" :key="key"  :type="value.type" @click="onAction(value, scope.row)" size="mini" >
                        <i style="margin-right: 5px;" class="fa" :class="value.icon"></i>{{value.text}}</el-button>
                    </span>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>
<script>
    import { CodeToText } from 'element-china-area-data'
    export default {
        props: ['table_data'],
        data() {
            return {
                table1: {},
                CodeToText:CodeToText
            }
        },
        mounted() {

        },
        methods: {
            onSort(column) {
                this.$emit('sort',{ prop: column.prop, order: column.order});
            },
            onAction(act, obj) {
                this.$emit('action', {act: act,data: obj});
            },
            onSwitch(value) {
                this.$emit('switch', value);
            }
        }
    }
</script>
<style scoped>
    .table-container {
        border-top: 1px solid #f4f4f4;
    }
    .image-box {
        display: flex;
        align-items: center;
        background-size: 100%;
    }

    .image {
        width: auto;
        height: auto;
        max-width: 80%;
        border: 1px solid #aaa;
        padding: 2px;
    }
    .avatar{
        width:30px;
        hength:30px;
    }
    .label-info {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        background-color: #909399;
    }

    .label-success {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        background-color: #67C23A;
    }

    .label-primary {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        background-color: #409EFF;
    }

    .label-warning {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        background-color: #f0ad4e;
    }

    .label-danger {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
        background-color: #d9534f;
    }
    .table-container .demo-table-expand {
        font-size: 0;
    }
    .el-table__expanded-cell .demo-table-expand .el-form-item__label{

        width: 90px;
        color: #99a9bf;

    }
    .table-container .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
</style>