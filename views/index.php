<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product listing</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/Paginator.js"></script>
    <script src="/script.js"></script>
</head>

<body>
    <label for="prevPage"></label>
    <button type="button" id="prevPage" name="prevPage">Previous Page</button>

    <label for="nextPage"></label>
    <button type="button" id="nextPage" name="nextPage">Next Page</button>


    <table id="productsTable">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
    </table>
    <!-- <script type="text/javascript">
        function updateTable(pageVal) {
            alert("pressed button");
            $.getJSON(url = "/api/products?page=" + pageVal + "&limit=5",
                function (json) {
                    var tr;
                    for (var i = 0; i < json.length; i++) {
                        tr = $('<tr/>');
                        tr.append("<td>" + json[i].name + "</td>");
                        tr.append("<td>" + json[i].description + "</td>");
                        tr.append("<td>" + json[i].price + "</td>");
                        $('table').append(tr);
                    }
                    $('#productsTable').append(tr)

                });

        };


        var pageButton = document.getElementById("getPageNumber");
        var pageNumber = document.getElementById("page");
        pageButton.addEventListener('click', function () {

            updateTable(pageNumber.value);
        });
    </script> -->
</body>

</html>