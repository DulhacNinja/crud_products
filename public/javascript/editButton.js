// button shall redirect to a page where the user can edit the product (new view required)
function createEditButton(product) {
    var editElm = document.createElement('td');
    editElm.innerHTML = `
<button type="button" class="btn btn-success"">
Edit
</button>`
    // it should only trigger the edit button event
    editElm.addEventListener('click', e => redirectViewEdit(product));
    return editElm;
}

function redirectViewEdit(product) {
    window.location.href = `/edit?id=${product['id']}`;
}