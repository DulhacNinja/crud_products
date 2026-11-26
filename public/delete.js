function createDeleteModal(product) {

    const modalElm = document.getElementsByClassName('modal')[0];
    console.log(modalElm);
    var modalInner = `
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        DELETE PRODUCT ${product['name']}?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">DELETE</button>
      </div>
    </div>
  </div>
</div>
    `
    modalElm.innerHTML = modalInner;
    const modalDeleteElm = modalElm.getElementsByClassName("btn btn-primary")[0];
    // console.log(modalDeleteElm);
    modalDeleteElm.addEventListener('click', e => deleteProduct(product));
    // document.querySelector('body').className = "modal-open";
    return modalElm;

}

// first create function to add functionality to the "delete" button
function deleteProduct(product){
    // fetch(`/api/products?id=${product['id']}`, {method: 'DELETE'}).then(res => res.json()).then(data => alert(data));
    alert("Pressed delete!");
    const modalElm = document.getElementsByClassName('modal')[0];
    modalElm.innerHTML = '';
}