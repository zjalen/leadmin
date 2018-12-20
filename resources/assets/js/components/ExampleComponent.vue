<template>
    <div class="container">
        <el-row>
            <el-col :span="6">
                <info_box style="margin-bottom: 10px;" :color="box1.color" :icon="box1.icon" :title="box1.title" :description="box1.description"></info_box>

            </el-col>
            <el-col :span="12">
                <info_box :color="box2.color" :icon="box2.icon" :title="box2.title" :description="box2.description" ></info_box>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24">
                <div class="box">
                    <div class="box-header">
                        <el-button type="primary" @click="onDo('add')">添加</el-button>
                        <el-button type="success" @click="onDo('filter')">筛选</el-button>
                        <el-button type="danger" @click="onDo('export')">导出</el-button>
                    </div>
                    <grid_table :table_data="table1" :actions="table1.actions" @switch="onSwitch" @sort="onSort" @action="onAction"></grid_table>
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
                        <custom_form :form_data="form1" @action="onAct"></custom_form>
                    </el-dialog>
                </div>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="16">
                <custom_form :form_data="form1" @action="onAct"></custom_form>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="24">
                <custom_tree :tree_data="tree_data" @action="onAct"></custom_tree>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import info_box from './widgets/InfoBox';
    import grid_table from './grids/Table';
    import custom_form from './forms/Form';
    import custom_tree from './grids/Tree';
    export default {
        components: {
            grid_table,
            info_box,
            custom_form,
            custom_tree
        },
        data() {
            return {
                dialogFormVisible: false,
                page_size: 20,
                current_page: 1,
                box1: {
                    color: '#ff6b5f',
                    icon: 'fa-users',
                    title: '180998',
                    description: '用户(人)',
                    link: 'http://www.baidu.com'
                },
                box2: {
                    color: '#41cac0',
                    icon: 'fa-home',
                    title: '20000',
                    description: '房屋(间)',
                },
                table1:{
                    header: [
                        {title:'图片',name:'avatar', width: 100, image: true},
                        {title:'普通',name:'text', width: 100},
                        {title:'状态默认',name:'id', width: 120, text_tag: 'label-info', sortable: 'custom'},
                        {title:'状态',name:'state', width: 100, text_tag: 'label-danger'},
                        {title:'状态1',name:'state1', width: 100, text_tag: 'label-success'},
                        {title:'状态2',name:'state2', width: 100, text_tag: 'label-warning'},
                        {title:'状态3',name:'state3', width: 100, text_tag: 'label-primary'},
                        {title:'切换',name:'switch', width: 100, switch: true},
                        {title:'自定义动作',name:'url', width: 200, text_tag: 'label-primary', action: 'jump'},
                    ],
                    body: [
                        {avatar:'https://ss0.bdstatic.com/k4oZeXSm1A5BphGlnYG/icon/95584.png',
                            text:'普通文本',id: 'info', state:"danger",state1: 'success',
                            state2: 'warning', state3: 'primary', switch: true, url: 'http://www.baidu.com',}
                    ],
                    count: 1,
                },
                actions: [
                    {action:'edit', type: 'primary', text: '编辑', icon: 'fa-edit'},
                    {action:'delete', type: 'danger',  text: '删除', icon: 'fa-trash'},
                    {action:'custom', type: 'info',  text: '自定义', icon: 'fa-home'}
                ],
                form1: {
                    headers: [
                        {title: '姓名', name: 'name'},
                        {title: '下拉', name: 'type', select: [{label: 'A型产品', value: 1},{label: 'B型产品', value: 2}]},
                        {title: '多选', name: 'multiselect', multiselect: [{name: 'JAVA', id: 1},{name: 'C语言', id: 2},{name: 'IOS', id: 4},{name: 'ANDROID', id: 5}]},
                        {title: '单选', name: 'radio', radio: [{name: 'PHP', id: 2},{name: 'PYTHON', id: 3}]},
                        {title: '打开', name: 'open', switch: true},
                        {title: '日期', name: 'date', date: true},
                        {title: '时间', name: 'datetime', datetime: true},
                        {title: '大文本', name: 'textarea', textarea: true},
                    ],
                    body: {name: '张三', type: 2, multiselect:[1, 4], radio: 3, open:true, date: '2018-11-05 11:22:00', datetime: '2018-11-11 11:22:00',textarea:'这是多行文本的演示，这是多行文本的演示，这是多行文本的演示，这是多行文本的演示，这是多行文本的演示，这是多行文本的演示，'},
                },
                tree_data: [{
                    id: 1,
                    label: '一级 1',
                    children: [{
                        id: 4,
                        label: '二级 1-1',
                        children: [{
                            id: 9,
                            label: '三级 1-1-1'
                        }, {
                            id: 10,
                            label: '三级 1-1-2'
                        }]
                    }]
                }, {
                    id: 2,
                    label: '一级 2',
                    children: [{
                        id: 5,
                        label: '二级 2-1'
                    }, {
                        id: 6,
                        label: '二级 2-2'
                    }]
                }, {
                    id: 3,
                    label: '一级 3',
                    children: [{
                        id: 7,
                        label: '二级 3-1'
                    }, {
                        id: 8,
                        label: '二级 3-2',
                        children: [{
                            id: 11,
                            label: '三级 3-2-1'
                        }, {
                            id: 12,
                            label: '三级 3-2-2'
                        }, {
                            id: 13,
                            label: '三级 3-2-3'
                        }]
                    }]
                }],
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            onAction(obj) {
                console.log(obj);
            },
            onSwitch(data) {
                console.log(data);
            },
            onSort(obj) {
                console.log(obj);
            },
            onAct(obj) {
                console.log(obj);
            },
            onDo(act){
                console.log(act);
                if (act === 'filter'){
                    this.dialogFormVisible = true;
                }
            },
            initData() {
                console.log('重新获取数据');
            }
        }

    }
</script>

<style scoped>
    .container {
        padding: 30px;
    }
    .pages {
        margin: 20px 20px 20px 0;
        display: flex;
        justify-content: flex-end;
    }
</style>
