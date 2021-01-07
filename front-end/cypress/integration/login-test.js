describe('登陆测试', () => {
    beforeEach(() => {
        cy.clearLocalStorage()
    })

    it('未登录访问首页时，自动跳转到登陆页面', () => {
        cy.visit('/')
        cy.get('h2').should('contain.text', '登陆')
        cy.url().should('contain', '/#/login')

        // 点击注册跳转到注册页面
        cy.get('button').contains('注册新用户').click()
        cy.url().should('contain', '/#/register')
    })

    it('登陆失败', () => {
        cy.visit('#/login')
        cy.get('form').find('input:nth(0)').type('123')
        cy.get('form').should('contain.text', '请输入有效邮箱地址')

        cy.get('form').find('input:nth(0)').type(Date.now() + '@123.com')
        cy.get('form').find('input:nth(1)').type('secret')
        cy.get('button').contains('登陆').click()
        cy.get('.el-message__content').should('contain.text', '邮箱或密码错误')
    })

    it('登陆成功', () => {
        cy.visit('#/login')
        cy.get('form').find('input:nth(0)').type('tom.student@jerry.com')
        cy.get('form').find('input:nth(1)').type('secret')
        cy.get('button').contains('登陆').click()
        cy.should(() => {
            expect(localStorage.getItem('access_token')).to.be.not.null
            expect(localStorage.getItem('access_token_expires_at')).to.be.not.null
        })
        cy.url().should('eq', Cypress.config().baseUrl + '/?#/')
    })
})
