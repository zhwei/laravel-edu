export function login(userInfo) {
    localStorage.setItem('access_token', userInfo.access_token)
    localStorage.setItem('access_token_expires_at', userInfo.expires_at)
    localStorage.setItem('name', userInfo.name)
    localStorage.setItem('role', userInfo.role)
}

export function logout() {
    localStorage.removeItem('access_token')
    localStorage.removeItem('access_token_expires_at')
    localStorage.removeItem('name')
    localStorage.removeItem('role')
}

export function getUserInfo() {
    const access_token = getAccessToken()
    if (!access_token) {
        return null
    }

    return {
        name: localStorage.getItem('name'),
        role: localStorage.getItem('role'),
        access_token,
        expires_at: localStorage.getItem('access_token_expires_at'),
    }
}

export function getAccessToken() {
    const expiresAt = localStorage.getItem('access_token_expires_at')
    if (expiresAt * 1000 < Date.now()) {
        return null;
    }
    return localStorage.getItem('access_token')
}
