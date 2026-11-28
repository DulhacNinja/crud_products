
// creates row elements to populate the table with products
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

    // for every field of the product, make it clickable and redirect to GET prod id
    for(let i = 0; i < trElm.children.length; i++){
        trElm.children[i].addEventListener('click', e => redirectViewShow(prod));
    }

    // add the delete button for product with separate event handler, so it doesn't trigger GET prod id
    trElm.appendChild(createDeleteButton(prod));

    // add the edit button that will redirect to edit form
    trElm.appendChild(createEditButton(prod));
    return trElm;
}

// overwrites current table with new table containing products from fresh GET page
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

    // add rows with products
    for (let i = 0; i < products.length; i++) {
        tableBodyElm.appendChild(createTableEntry(products[i]));
    }

    //render pagination options
    renderPagination(pageData);
}

function redirectViewShow(product) {
    window.location.href = `/show?id=${product['id']}`;
}