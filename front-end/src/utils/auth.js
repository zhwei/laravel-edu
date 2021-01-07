export function login(userInfo) {
    localStorage.setItem('access_token', userInfo.access_token)
    localStorage.setItem('access_token_expires_at', userInfo.expires_at)
    localStorage.setItem('user', JSON.stringify(userInfo))
}

export function logout() {
    localStorage.removeItem('access_token')
    localStorage.removeItem('access_token_expires_at')
    localStorage.removeItem('name')
    localStorage.removeItem('role')
    localStorage.removeItem('id')
    localStorage.removeItem('user')
}

export function getUserInfo() {
    const access_token = getAccessToken()
    if (!access_token) {
        return null
    }

    return JSON.parse(localStorage.getItem('user'))
}

export function getAccessToken() {
    const expiresAt = localStorage.getItem('access_token_expires_at')
    if (expiresAt * 1000 < Date.now()) {
        return null;
    }
    return localStorage.getItem('access_token')
}
