
// id of the product to be deleted, it should show a modal
function createDeleteButton(product){
    var deleteElm = document.createElement('td');
    deleteElm.type = "button";
    deleteElm.className = "btn btn-primary";
    deleteElm.innerText = "Delete";
    deleteElm.setAttribute("data-bs-toggle", "modal");
    deleteElm.setAttribute("data-bs-target", "#exampleModal");
    deleteElm.addEventListener('click', e => createDeleteModal(product));
    
    return deleteElm;
}



// page = what page should this redirect to
// disabled = if it should be grayed out
// active = if it should be coloured in as current active page
function createPaginationEntry(page, disabled = false, active = false) {

    var modifier = '';
    if (disabled) {
        modifier = " disabled";
    }
    if (active) {
        modifier = " active";
    }
    var elmData = `<a class="page-link">${page}</a>`;
    var liElm = document.createElement(`li`);
    liElm.className = `page-item${modifier}`;
    liElm.innerHTML = elmData;
    liElm.addEventListener('click', e => { fetchTable(liElm.innerText, 10) });
    console.log(liElm);
    return liElm;

}

function createTableEntry(prod) {
    var elmData =
        `
        <th scope="row">${prod['id']}</th>
        <td>${prod['name']}</td>
        <td>${prod['price']}</td>
        <td>${prod['availabilityDate']}</td>
        <td>${prod['isInStock']}</td>
        `
    var trElm = document.createElement('tr');
    trElm.innerHTML = elmData;

    trElm.addEventListener('click', e => { console.log("Should redirect to product page") });
    trElm.appendChild(createDeleteButton(prod));
    return trElm;
}

function renderPagination(pageData) {

    const tableElm = document.getElementsByClassName("table")[0]

    // render pagination options
    const limit = pageData['limit'];
    const curPage = pageData['page'];
    const totalPages = pageData['totalPages'];
    const totalProducts = pageData['totalProducts'];
    const curProducts = pageData['products'].length;

    const paginationElm = document.querySelector(".pagination");
    for (let i = 1; i <= totalPages; i++) {

        if (i == curPage) {
            paginationElm.appendChild(createPaginationEntry(i, false, true));
        }
        else {
            paginationElm.appendChild(createPaginationEntry(i, false, false));
        }
    }
}

function renderTable(pageData) {

    const tableElm = document.querySelector("table");
    const paginationElm = document.querySelector("nav");

    tableElm.innerHTML = `        
        <thead>
        </thead>
        <tbody>
        </tbody>
        `;
    paginationElm.innerHTML = `
        <ul class="pagination">
        </ul>
    `;
    // console.log(tableElm.innerHTML);
    // console.log(paginationElm.innerHTML);

    // render table header
    const tableHeadElm = document.querySelector("thead");
    tableHeadElm.innerHTML = `
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Availability Date</th>
        <th scope="col">In Stock</th>
        <!-- <th scope="col">Description</th> -->
    </tr>
    `

    // render table body
    const products = pageData['products'];
    var tableBodyElm = document.querySelector("tbody");

    for (let i = 0; i < products.length; i++) {
        tableBodyElm.appendChild(createTableEntry(products[i]));
    }

    //render pagination options
    renderPagination(pageData);
}

function fetchTable(page, limit) {
    var url = `/api/products?page=${page}&limit=${limit}`;
    fetch(url).then(res => res.json())
        .then(pageData => renderTable(pageData))
}