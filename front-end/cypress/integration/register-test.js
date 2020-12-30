describe('注册测试', () => {
    beforeEach(() => {
        cy.clearLocalStorage()
    })

    it('注册失败', () => {
        cy.visit('#/register')

        // 测试必填
        cy.get('form').find('input:nth(0)').focus()
        cy.get('form').find('input:nth(1)').focus()
        cy.get('form').find('input:nth(2)').focus()
        cy.get('form').find('input:nth(2)').blur()
        cy.get('form').should('contain.text', '请输入邮箱地址')
        cy.get('form').should('contain.text', '请输入姓名')
        cy.get('form').should('contain.text', '请输入密码')

        // 测试合法邮箱
        cy.get('form').find('input:nth(0)').type('123')
        cy.get('form').should('contain.text', '请输入有效邮箱地址')

        // 测试不成功
        // - 密码不一致
        cy.get('form').find('input:nth(2)').type('secret')
        cy.get('form').find('input:nth(3)').type('s')
        cy.get('form').should('contain.text', '两次输入的密码不一致')
        cy.get('form').find('input:nth(3)').clear().type('secret')
        cy.get('form').should('not.contain.text', '两次输入的密码不一致')
        // - 邮箱重复
        cy.get('form').find('input:nth(0)').clear().type('tom.student@jerry.com')
        cy.get('form').find('input:nth(1)').type(Date.now())
        cy.get('button').contains('注册').click()
        cy.get('.el-message__content').should('contain.text', 'The email has already been taken')

        // 注册成功，最终跳转到首页
        cy.get('form').find('input:nth(0)').clear().type(Date.now() + '@123.com')
        cy.get('button').contains('注册').click()
        cy.get('.el-message__content').should('contain.text', '注册成功')
        cy.url().should('eq', Cypress.config().baseUrl + '/#/')
    })

})
