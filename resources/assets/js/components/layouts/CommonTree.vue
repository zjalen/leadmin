<template>
    <div class="tree-content">
        <div class="content-header">
            {{tree_data.title}}
            <span class="desc">{{tree_data.description}}</span>
        </div>
        <div class="box" v-loading="loading">
            <el-row class="box-tree">
                <el-col :span="24">
                    <custom_tree @headerClick="onHeaderClick" @action="onAction" :tree_data="tree_data.data"></custom_tree>
                </el-col>
            </el-row>
        </div>
    </div>
</template>
<script>
    import custom_tree from '../grids/Tree';
    export default {
        props: ['tree_data'],
        components: {
            custom_tree,
        },
        data() {
            return {
                loading: true,
            }
        },
        computed: {

        },
        created() {
        },
        mounted() {
            this.loading = false;
        },
        methods: {
            onHeaderClick(obj) {
                console.log(obj.act);
                switch (obj.act) {
                    case 'create':
                        if (obj.id) {
                            window.location.href = window.location.href + '/create?id=' + obj.id;
                        }else {
                            window.location.href = window.location.href + '/create';
                        }
                        break;
                    case 'save':
                        console.log(obj.data);
                        this.onSave(obj.data);
                        break;
                }
            },
            onAction(obj) {
                switch (obj.act) {
                    case 'edit':
                        window.location.href = window.location.href + '/' + obj.id + '/edit';
                        break;
                    case 'delete':
                        this.onDelete(obj.id);
                        break;
                }
            },
            // 更新，switch开关切换
            onSave(data) {
                let url = window.location.href + '/saveMenus';
                let that = this;
                this.loading = true;
                this.$axios.post(url, data).then(function (response) {
                    // console.log(response.data);
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
                    console.log(response);//发生错误时执行的代码
                    that.loading = false;
                });
            },
            onDelete(id) {
                let that = this;
                this.$confirm('是否确认删除？')
                    .then(() => {
                        let url = window.location.href + '/' + id;
                        this.loading = true;
                        this.$axios.delete(url, {}).then(function (response) {
                            // console.log(response.data);
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
                            console.log(response);//发生错误时执行的代码
                            that.loading = false;
                        });
                    })
                    .catch(_ => {
                    });
            },
        }
    }
</script>
<style scoped>
    .tree-content {
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

    .box-tree {
        margin: 15px;
    }

</style>