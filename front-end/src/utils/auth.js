export function login(access_token, expires_at) {
    localStorage.setItem('access_token', access_token)
    localStorage.setItem('access_token_expires_at', expires_at)
}

export function logout() {
    localStorage.removeItem('access_token')
    localStorage.removeItem('access_token_expires_at')
}

export function getAccessToken() {
    const expiresAt = localStorage.getItem('access_token_expires_at')
    if (expiresAt * 1000 < Date.now()) {
        return null;
    }
    return localStorage.getItem('access_token')
}
