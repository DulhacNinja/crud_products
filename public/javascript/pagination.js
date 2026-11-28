// page = what page should this redirect to
// disabled = if it should be grayed out
// active = if it should be coloured in as current active page
function createPaginationEntry(text, page, disabled = false, active = false) {

    var modifier = '';
    if (disabled) {
        modifier = " disabled";
    }
    if (active) {
        modifier = " active";
    }
    var elmData = `<a class="page-link">${text}</a>`;
    var liElm = document.createElement(`li`);
    liElm.className = `page-item${modifier}`;
    liElm.innerHTML = elmData;
    liElm.addEventListener('click', e => {
        fetchTable(page);
    });
    return liElm;

}

// overwrite pagination component with data from fresh GET page
function renderPagination(pageData) {

    // variables from the GET page response
    const limit = pageData['limit'];
    const curPage = pageData['page'];
    const totalPages = pageData['totalPages'];
    const totalProducts = pageData['totalProducts'];
    const curProducts = pageData['products'].length;

    // display indexes of items being shown and how many total items
    renderCountText(limit, curPage, totalProducts, curProducts);

    const paginationElm = document.querySelector(".pagination");

    // if we're on the current page, add a greyed out "Previous" button
    if (curPage <= 1) {
        paginationElm.appendChild(createPaginationEntry("Prev", 1, true, false));
    }
    // else add a functional one
    else {
        paginationElm.appendChild(createPaginationEntry("Prev", curPage - 1, false, false));
    }

    for (let i = 1; i <= totalPages; i++) {

        if (i == curPage) {
            paginationElm.appendChild(createPaginationEntry(i, i, false, true));
        }
        else {
            paginationElm.appendChild(createPaginationEntry(i, i, false, false));
        }
    }

    // same logic for next button
    if (curPage >= totalPages) {
        paginationElm.appendChild(createPaginationEntry("Next", totalPages, true, false));
    }
    // else add a functional one
    else {
        paginationElm.appendChild(createPaginationEntry("Next", curPage + 1, false, false));
    }

}

function renderCountText(limit, curPage, totalProducts, curProducts){
    var firstIndex = limit * (curPage-1) + 1;
    var lastIndex = limit * (curPage-1) + curProducts;
    
    const paginationElm = document.getElementById("countText");
    paginationElm.innerHTML = `Displaying ${firstIndex} to ${lastIndex} out of ${totalProducts}`

}
