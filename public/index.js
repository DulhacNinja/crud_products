
// for now limit is global
limit = 5;
globalPageData = '';
function fetchTable(page) {
    // if we're truing to fetch page 0, that means, page 1 has failed, cause it's empty
    if (page == 0) {
        alert("PAGE 0 HAS BEEN REACHED, THERE AREN'T ANY PRODUCTS ON BACKEND DB");
        return;
    }
    var url = `/api/products?page=${page}&limit=${limit}`;
    fetch(url).then(res => res.json())
        .then(pageData => {
            globalPageData = pageData;
            // if current page returns error, that means it's empty, go back one page
            if (globalPageData.hasOwnProperty('error')) {
                fetchTable(page - 1);
            }
            // if it doesn't return error, it's fine to render it
            else {
                renderTable(pageData);
            }
        })
}

fetchTable(1);
