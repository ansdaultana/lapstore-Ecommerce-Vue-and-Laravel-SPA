export function setUser(state, user) {
    state.user.data = user;
    localStorage.setItem('USER_DATA', JSON.stringify(user));
}

export function setToken(state, token) {
    state.user.token = token;
    if (token) {
        sessionStorage.setItem('TOKEN', token);

    } else {
        sessionStorage.removeItem('TOKEN');
    }
}

export function setProducts(state, Products) {
    state.Products = Products;

}

export function setProduct(state,Product)
{
    state.Product=Product;
}