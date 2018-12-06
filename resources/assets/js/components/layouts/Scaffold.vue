<template>
    <div class="demo-block" v-loading="loading" style="width: 95%; margin: 15px;">
        <div style="margin-left: 20px;"> <h4>脚手架工具</h4>自动生成 MVCR ，一键生成页面</div>
        <el-form ref="form" :model="config" style="width: 100%" :rules="rules" label-width="80px">
            <el-form-item label="数据表" prop="table">
                <el-input v-model="config.table"></el-input>
            </el-form-item>
            <el-form-item label="模型" prop="model">
                <el-input v-model="config.model"></el-input>
            </el-form-item>
            <el-form-item label="请求验证" prop="controller">
                <el-input v-model="config.request"></el-input>
            </el-form-item>
            <el-form-item label="控制器" prop="controller">
                <el-input v-model="config.controller"></el-input>
            </el-form-item>

            <el-form-item label="">
                <el-checkbox v-model="config.create_migration" label="创建migration" name="migration"></el-checkbox>
                <el-checkbox v-model="config.create_model" label="创建Model" name="model"></el-checkbox>
                <el-checkbox v-model="config.create_request" label="创建Request" name="request"></el-checkbox>
                <el-checkbox v-model="config.create_controller" label="创建Controller" name="controller"></el-checkbox>
                <el-checkbox v-model="config.run_migration" label="运行migration" name="run_migration"></el-checkbox>
            </el-form-item>

            <div style="margin-left: 20px;"> <h4>数据表设计(如默认主键为id无需填写id字段)</h4></div>
            <div style="width: 100%;" class="flex-center">
                <el-table
                        :data="tableData"
                        stripe
                        fit
                        border
                        style="width: 100%">
                    <el-table-column
                            label="字段名">
                        <template slot-scope="scope">
                            <el-input v-model="scope.row.name" placeholder="请输入字段名"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="类型">
                        <template slot-scope="scope">
                            <el-select v-model="scope.row.type" filterable placeholder="请选择">
                                <el-option
                                        v-for="item in db_types"
                                        :key="item"
                                        :label="item"
                                        :value="item">
                                </el-option>
                            </el-select>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="长度">
                        <template slot-scope="scope">
                            <el-input v-model="scope.row.length" placeholder="字段长度"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="是否允许为空">
                        <template slot-scope="scope">
                            <el-checkbox v-model="scope.row.nullable" label="允许为空" border></el-checkbox>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="是否为索引">
                        <template slot-scope="scope">
                            <el-checkbox v-model="scope.row.index" label="索引" border></el-checkbox>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="是否唯一">
                        <template slot-scope="scope">
                            <el-checkbox v-model="scope.row.is_unique" label="唯一" border></el-checkbox>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="默认值">
                        <template slot-scope="scope">
                            <el-input v-model="scope.row.default" placeholder="请输入默认值"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="备注描述">
                        <template slot-scope="scope">
                            <el-input v-model="scope.row.comment" placeholder="请输入备注"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="primary" size="mini" @click="onAdd(scope.$index)">添加</el-button>
                            <el-button style="margin-left: 0" type="danger" size="mini" @click="onDelete(scope.$index)">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div style="margin: 20px;display: flex;justify-content: flex-end;align-items: center;justify-items: center">
                <el-input placeholder="请输入字段名" style="width: 180px; margin-right: 10px;" v-model="table_config.table_index">
                    <template slot="prepend">默认主键</template>
                </el-input>
                <el-checkbox v-model="table_config.include_timestamp" label="自动记录created_at和updated_at"></el-checkbox>
                <el-checkbox v-model="table_config.soft_delete" label="软删除deleted_at"></el-checkbox>
                <el-button style="margin-left: 10px;" type="primary" @click="onSubmit()">提交</el-button>
            </div>
        </el-form>
    </div>
</template>
<script>
    export default {
        props: [ 'src' ],
        components: {
        },
        data() {
            return {
                loading: true,
                config: {
                    table: '',
                    model: 'App\\Models\\',
                    request: 'App\\Leadmin\\Requests\\',
                    controller: 'App\\Leadmin\\Controllers\\',
                    create_migration: true,
                    create_model: true,
                    create_request: true,
                    create_controller: true,
                    run_migration: true
                },
                table_config: {
                    table_index: 'id',
                    include_timestamp: true,
                    soft_delete: false
                },
                tableData: [
                    {
                        name: '',
                        type: 'string',
                        length: '',
                        default: '',
                        comment: '',
                        nullable: false,
                        is_index: false,
                        is_unique: false,
                    }
                ],
                db_types: [
                    'string', 'integer', 'text', 'float', 'double', 'decimal', 'boolean', 'date', 'time',
                    'dateTime', 'timestamp', 'char', 'mediumText', 'longText', 'tinyInteger', 'smallInteger',
                    'mediumInteger', 'bigInteger', 'unsignedTinyInteger', 'unsignedSmallInteger', 'unsignedMediumInteger',
                    'unsignedInteger', 'unsignedBigInteger', 'enum', 'json', 'jsonb', 'dateTimeTz', 'timeTz',
                    'timestampTz', 'nullableTimestamps', 'binary', 'ipAddress', 'macAddress',
                ],
                rules: {
                    table: [
                        {required: true, message: '输入不能为空', trigger: 'blur'},
                    ],
                    controller: [
                        {required: true, message: '输入不能为空', trigger: 'blur'},
                    ],
                    model: [
                        {required: true, message: '输入不能为空', trigger: 'blur'},
                    ],
                    field: [
                        {required: true, message: '输入不能为空', trigger: 'blur'},
                    ]
                }
            }
        },
        mounted() {
            this.loading = false;
        },
        methods:{
            onChange() {
            },
            onDelete(i) {
                if (this.tableData.length > 1) {
                    this.tableData.splice(i, 1);
                }
            },
            onAdd(i) {
                let data = {
                    name: '',
                    type: 'string',
                    length: '',
                    default: '',
                    comment: '',
                    nullable: false,
                    is_index: false,
                    is_unique: false,
                };
                this.tableData.splice(i+1, 0, data);
            },
            onSubmit() {
                if (this.config['create_controller']){
                    if (!this.config['controller']){
                        this.$alert("控制器不能为空");
                        return
                    }
                }
                if (this.config['create_migration'] || this.config['run_migration']) {
                    if (!this.config['table']) {
                        this.$alert("表名不能为空");
                        return
                    }
                }
                if (this.config['create_model']) {
                    if (!this.config['model']) {
                        this.$alert("模型不能为空");
                        return
                    }
                }
                for (let item of this.tableData){
                    if (!item.name){
                        this.$alert("字段名不能为空");
                        return
                    }
                }
                let that = this;
                axios.defaults.headers.common = {
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                };
                this.loading = true;
                axios.post('scaffold', {
                    config: this.config,
                    table_config: this.table_config,
                    table_data: this.tableData
                })
                    .then(function (response) {
                        that.$alert(response.data.error_message);
                        that.loading = false;
                    })
                    .catch(function (error) {
                        that.$alert(error);
                        that.loading = false;
                    });
            }
        }
    }
</script>
<style scoped>
</style>