// id of the product to be deleted, it should show a modal
function createDeleteButton(product) {
  var deleteElm = document.createElement('td');
  deleteElm.innerHTML = `
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalLive">
Delete
</button>`

  deleteElm.addEventListener('click', e => renderDeleteModal(product));
  return deleteElm;
}


// shows the modal on screen and adds event listener to "delete" button
function renderDeleteModal(product) {
  const modalElm = document.querySelector('.myModal');
  modalElm.innerHTML = `
    <!-- https://www.w3schools.com/bootstrap/bootstrap_modal.asp -->
    <!-- Modal -->
    <div id="exampleModalLive" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">WARNING</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>FOUND THE MODAL BODY!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
 `

  const modalBodyElm = document.querySelector('.modal-body');
  modalBodyElm.innerHTML = `<p>Are you sure you want to delete ${product['name']}</p>`

  const modalFooterElm = document.querySelector('.modal-footer');

  const modalDeleteElm = modalFooterElm.querySelectorAll('button')[1];
  modalDeleteElm.addEventListener('click', e => deleteProduct(product));

}


// first create function to add functionality to the "delete" button
function deleteProduct(product) {
  fetch(`/api/products?id=${product['id']}`, { method: 'DELETE' })
    .then(res => res.json())
    .then(data => alert(`deleted prod id=${data['id']}`));
  
    fetchTable(globalPageData['page']);

}