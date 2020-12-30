export const ALL_MENUS = [
    {
        name: '学生',
        roles: ['student', 'system_admin'],
        icon: 'el-icon-user-solid',
        children: [
            {name: '学校信息', path: '/'},
            {name: '老师们', path: '/'},
            {name: '我关注的', path: '/'},
        ],
    },

    {
        name: '老师',
        roles: ['teacher', 'system_admin'],
        icon: 'el-icon-s-custom',
        children: [
            {name: '申请新学校', path: '/schools/create'},
            {name: '学校列表', path: '/schools/list'},
            {name: '学生列表', path: '/teachers/students/teaching'},
            {name: '关注我的', path: '/teachers/students/following'},
        ],
    },

    {
        name: '学校管理员',
        roles: ['teacher', 'school_admin', 'system_admin'],
        icon: 'el-icon-school',
        children: [
            {name: '学校列表', path: '/schools/list'},
        ],
    },

    {
        name: '系统管理员',
        roles: ['system_admin'],
        icon: 'el-icon-cpu',
        children: [
            {name: '审批学校申请', path: '/'}
        ],
    },
]
