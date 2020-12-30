export default {
    email: [
        {required: true, message: '请输入邮箱地址'},
        {type: 'email', message: '请输入有效邮箱地址'},
    ],

    name: {
        type: 'string',
        required: true,
        message: '请输入姓名',
    },

    password: [
        {required: true, message: '请输入密码'},
    ],

}
