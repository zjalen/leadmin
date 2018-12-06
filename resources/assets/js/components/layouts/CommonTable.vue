<template>
    <div class="container">
        <div class="content-header">
            {{table_data.title}}
            <span class="desc">{{table_data.description}}</span>
        </div>
        <el-row :gutter="20">
            <el-col style="margin-bottom: 20px;" :xs="24" :sm="12" :md="6" :lg="6" :xl="6" :span="6" v-for="(value, key) in boxes" :key="key">
                <a :href="value.link">
                    <info_box :color="value.color" :icon="value.icon" :title="value.title" :description="value.description"></info_box>
                </a>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24">
                <div class="box">
                    <div class="box-header">
                        <el-button v-for="(value,key) in table_data.header_actions" :key="key"  :type="value.type" @click="onHeaderClick(value.action)" size="mini" >
                            <i style="margin-right: 5px;" class="fa" :class="value.icon"></i>{{value.text}}</el-button>
                    </div>
                    <grid_table v-loading="loading" :table_data="table1" @switch="onSwitch" @sort="onSort" @action="onAction"></grid_table>
                    <div class="pages">
                        <el-pagination
                                background
                                :page-size="page_size"
                                layout="total, prev, pager, next, jumper"
                                :current-page.sync="current_page"
                                @current-change="initData"
                                :total="table1.count">
                        </el-pagination>
                    </div>
                    <el-dialog title="筛选" :visible.sync="dialogFormVisible">
                        <custom_form :form_data="filters" @action="onAct"></custom_form>
                    </el-dialog>
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import info_box from '../widgets/InfoBox';
    import grid_table from '../grids/Table';
    import custom_form from '../forms/Form';
    export default {
        props: ['boxes','table_data','filters'],
        components: {
            grid_table,
            info_box,
            custom_form
        },
        data() {
            return {
                dialogFormVisible: false,
                page_size: 20,
                current_page: 1,
                search_params: '',
                sort_params: '',
                table1: {},
                params: {},
                loading: true,
            }
        },
        mounted() {
            this.table1 = this.table_data;
            this.loading = false;
        },
        computed: {

        },
        methods: {
            // 数据初始化和刷新
            initData() {
                let that = this;
                let url = window.location.href;
                let params = this.params;
                params['skip'] = (this.current_page - 1) * this.page_size;
                params['limit'] = this.page_size;
                this.loading = true;
                this.$axios.get(url, {params}).then(function (response) {
                    if (!response.data.error_code) {
                        that.table1 = response.data;
                        that.dialogFormVisible = false;
                    } else {
                        that.$message({
                            message: response.data.error_message,
                            type: 'error'
                        });
                    }
                    that.loading = false;
                }).catch(function (response) {
                    that.loading = false;
                });
            },
            // 表格顶部按钮操作
            onHeaderClick(act) {
                switch (act) {
                    case 'filter':
                        this.dialogFormVisible = true;
                        break;
                    case 'create':
                        window.location.href = window.location.href + '/create';
                        break;
                    case 'export':

                        break;
                }
            },
            // 表格内动作
            onAction(obj) {
                switch (obj.act) {
                    case 'edit':
                        window.location.href = window.location.href + '/' + obj.data.id + '/edit';
                        break;
                    case 'delete':
                        this.onDelete(obj.data.id);
                        break;
                }
            },
            onSwitch(data) {
                this.onUpdate(data);
            },
            // 排序
            onSort(obj) {
                this.params['_sort'] = {};
                this.params['_sort']['column'] = obj.prop;
                if (obj.order === "ascending") {
                    this.params['_sort']['order'] = 'asc';
                } else if (obj.order === "descending") {
                    this.params['_sort']['order'] = 'desc';
                }
                this.current_page = 1;
                this.initData();
            },
            // 搜索框按钮回调
            onAct(obj) {
                this.params['filters'] = {};
                switch (obj.act) {
                    case 'cancel':
                        this.dialogFormVisible = false;
                        break;
                    case 'submit':
                        for (let key in obj.data) {
                            if (obj.data.hasOwnProperty(key)) {
                                if (obj.data[key]) {
                                    this.params['filters'][key] = obj.data[key];
                                }
                            }
                        }
                        this.current_page = 1;
                        this.initData();
                        break;
                }
            },
            // 删除
            onDelete(id) {
                let that = this;
                this.$confirm('是否确认删除？')
                    .then(() => {
                        let url = window.location.href + '/' + id;
                        this.loading = true;
                        this.$axios.delete(url, {}).then(function (response) {
                            if (!response.data.error_code) {
                                that.$message({
                                    message: '删除成功',
                                    type: 'success'
                                });
                                that.initData();
                            } else {
                                that.$message({
                                    message: response.data.error_message,
                                    type: 'error'
                                });
                            }
                            that.loading = false;
                        }).catch(function (response) {
                            that.loading = false;
                        });
                    })
                    .catch(_ => {
                    });
            },
            // 更新，switch开关切换
            onUpdate(data) {
                let url = window.location.href + '/' + data.id;
                let that = this;
                this.loading = true;
                this.$axios.put(url, {data}).then(function (response) {
                    if (!response.data.error_code) {
                        that.$message({
                            message: '提交成功',
                            type: 'success'
                        });
                    } else {
                        that.$message({
                            message: response.data.error_message,
                            type: 'error'
                        });
                    }
                    that.loading = false;
                }).catch(function (response) {
                    that.loading = false;
                });
            },
        }
    }
</script>

<style scoped>
    .container {
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
    .pages {
        margin: 20px 20px 20px 0;
        display: flex;
        justify-content: flex-end;
    }

    .box {
        width: auto;
    }

</style>
