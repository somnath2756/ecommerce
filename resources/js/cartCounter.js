$(function(){
        $.ajax({
                    url: cartCountURL,
                    method: 'GET',
                    success: function(response) {
                        if (response.cartCount !== undefined) {
                            $('#cart-count').text(response.cartCount);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching cart count:', xhr);
                    }
        });
})