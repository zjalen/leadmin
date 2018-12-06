<template>
    <div class="tree-container">
        <div class="buttons">
            <el-button @click="onCreate()" type="primary" size="mini"><i class="fa fa-plus"></i> 新增</el-button>
            <el-button @click="onSave()" type="success" size="mini"><i class="fa fa-save"></i> 保存</el-button>
        </div>
        <el-tree
                ref="tree"
                :data="data3"
                node-key="id"
                default-expand-all
                :expand-on-click-node="false"
                :props="defaultProps"
                draggable >
            <span class="custom-tree-node" slot-scope="{ node, data }">
                <span><i class="fa" :class="node.data.icon"></i> {{ node.label }}</span>
                <span>
                    <el-button class="custom-button" @click="onCreate(node.data.id)" size="mini"><i class="fa fa-plus"></i></el-button>
                    <el-button class="custom-button" @click="onEdit(node.data.id)" size="mini"><i class="fa fa-edit"></i></el-button>
                    <el-button class="custom-button"  @click="onDelete(node.data.id)" size="mini"><i class="fa fa-trash"></i></el-button>
                </span>
            </span>
        </el-tree>
    </div>
</template>

<script>
    export default {
        props: ['tree_data'],
        data() {
            return {
                data3: [

                ],
                defaultProps: {
                    children: 'children',
                    label: 'title',
                    icon: 'icon',
                }
            };
        },
        mounted() {
          this.data3 = this.tree_data;
          console.log(this.data3);
        },
        methods: {
            onSave() {
                // console.log(this.data3);
                this.$emit('headerClick',{act: 'save', data: this.data3});
            },
            onCreate(id) {
                this.$emit('headerClick',{act: 'create', id: id});
            },
            onEdit(id) {
                this.$emit('action', {act: 'edit', id: id});
            },
            onDelete(id) {
                this.$emit('action', {act: 'delete', id: id});
            }
        }
    };
</script>
<style>
    .tree-box {
        padding: 5px;
    }
    .tree-container {
        font-weight: 500;
        font-size: 15px;
    }
    .el-tree-node.is-expanded > .el-tree-node__children {
        margin-left: 18px;
        padding-left: 0 !important;
    }
    .el-tree-node__content {
        border: 1px dashed #b6bcbf;
        margin: 3px 0;
        padding-left: 0 !important;
    }

    .buttons {
        margin-bottom: 15px;
    }
    .custom-tree-node {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
    }

    .custom-button {
        border: 1px dashed #b6bcbf;
    }

</style>