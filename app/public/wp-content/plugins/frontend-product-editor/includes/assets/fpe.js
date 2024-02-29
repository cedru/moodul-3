const fpe_tt = document.querySelector("#wp-admin-bar-fpe-trigger a");
if(fpe_tt){
    fpe_tt.addEventListener("click", function () {
        document.querySelector(".fpe-panel-trigger").click();
    });
}

// run a fetch request to get the current product data when '.fpe-at' is clicked

const fpe_at = document.querySelectorAll(".fpe-at");
if(fpe_at){
    fpe_at.forEach(function (el) {
        el.addEventListener("click", function (e) {
            e.preventDefault();
            // get the product id
            const product_id = el.getAttribute("data-product-id");

            // get panel overlay element
            const panel_overlay = document.querySelector(".fpe-panel-overlay");
            // set panel_overlay display to block
            panel_overlay.style.display = "flex";

            // get the product data
            fetch(`${fpe.ajax_url}?action=fpe_get_product_data&fpe_nonce=${fpe.security}&product_id=${product_id}`)
            .then( response => response.json())
            .then( data => { 
                // set panel overlay, display to none
                panel_overlay.style.display = "none";
                fpe.product = data.product;
                fpe.product_id = data.product.id;
                fpe.edit_mode = 'archive';
                document.querySelector(".fpe-panel-trigger").click();
             });
        })
    });
}
