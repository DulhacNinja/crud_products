class Paginator {

    // I should also somehow kow the number of pages available
    constructor(limit, totalPages) {
        
        this.index = 1;
        this.limit = limit;
        this.totalPages = totalPages;
        this.prevPageElm = document.getElementById("prevPage");
        this.nextPageElm = document.getElementById("nextPage");

        this.prevPageElm.addEventListener('click', event => this.fetchPrevPage(event));
        this.nextPageElm.addEventListener('click', event => this.fetchNextPage(event));
    }

    fetchNextPage(event) {
        if (this.index < this.totalPages){
            this.index += 1;
            this.fetchProductsPage(this.index);
        }

    }

    fetchPrevPage(event) {
        if (this.index > 1) {
            this.index -= 1
            this.fetchProductsPage(this.index);
        }
    }

    fetchProductsPage(page) {
        const xhttp = new XMLHttpRequest();
        // when the xhttp request is finished and the response is received, modify the table
        xhttp.onload = function () {

            let products = JSON.parse(this.response);
            for (let i = 0; i < products.length; i++) {
                console.log(products[i]);
            }
        }

        // prepare to send the request to this endpoint asynchronously
        url = "/api/products?page=" + page + "&limit=" + this.limit;
        xhttp.open("GET", url, true);

        // actually send the request to the server
        xhttp.send();
    }


}

async function createPaginator(limit) {

    var xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
        response = JSON.parse(this.response);
        var totalPages = response.pages;
        paginator = new Paginator(limit, totalPages);
    };

    url = "/api/products/pages?limit=" + limit;
    xhttp.open("GET", url, true); // false for synchronous request

    xhttp.send();

}

